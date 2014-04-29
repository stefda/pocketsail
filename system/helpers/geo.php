<?php

function haversine($lat1, $lng1, $lat2, $lng2) {
    $R = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLong = deg2rad($lng2 - $lng1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLong / 2) * sin($dLong / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $R * $c;
    return $d;
}

/**
 * @param float $lat Origin latitude in degrees
 * @param float $lng Origin longitude in degrees
 * @param float $d Distance from the origin
 * @param float $dir Direction in degrees
 * @return [newLat, newLng]
 */
function geo_proximity($lat, $lng, $d, $dir) {
    $R = 6378;
    $lat = deg2rad($lat);
    $lng = deg2rad($lng);
    $dir = deg2rad($dir);
    $newLat = asin(sin($lat) * cos($d / $R) + cos($lat) * sin($d / $R) * cos($dir));
    $newLng = $lng + atan2(sin($dir) * sin($d / $R) * cos($lat), cos($d / $R) - sin($lat) * sin($newLat));
    $res = new stdClass();
    $res->lat = rad2deg($newLat);
    $res->lng = rad2deg($newLng);
    return $res;
}

function bearing($lat1, $lng1, $lat2, $lng2) {
    $dLon = deg2rad($lng2) - deg2rad($lng1);
    $lat1 = deg2rad($lat1);
    $lng1 = deg2rad($lng1);
    $lat2 = deg2rad($lat2);
    $lng2 = deg2rad($lng2);
    $y = sin($dLon) * cos($lat2);
    $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLon);
    return rad2deg(atan2($y, $x));
}

function bearing_deg_to_dir($brng) {
    $dir = array('N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW');
    $brng = $brng < 0 ? 360 + $brng : $brng;
    return $dir[floor((($brng + 22.5) / 45) % 8)];
}

/**
 * Returns TRUE if the given point lies withing a polygon specified by
 * the given path.
 * 
 * @param array $path [[lat,lng],...]
 * @param array $point [lat,lng]
 * @return type
 */
function point_in_polygon($path, $point) {
    $polySides = count($path);
    $j = $polySides - 1;
    $inside = false;
    for ($i = 0; $i < $polySides; $i++) {
        if ($path[$i][0] < $point[0] && $path[$j][0] >= $point[0]
                || $path[$j][0] < $point[0] && $path[$i][0] >= $point[0]) {
            if ($path[$i][1] + ($point[0] - $path[$i][0])
                    / ($path[$j][0] - $path[$i][0])
                    * ($path[$j][1] - $path[$i][1]) < $point[1]) {
                $inside = !$inside;
            }
        }
        $j = $i;
    }
    return $inside;
}

/**
 * Calculate the area covered by a polygon specified by the given path.
 * 
 * @param array[array[float]] $path [[$lat0,$lng0],[$lat1,$lat2]...]
 * @return float
 */
function spherical_polygon_area($path) {

    $r = 6378;
    $lam1 = 0;
    $lam2 = 0;
    $beta1 = 0;
    $beta2 = 0;
    $cosB1 = 0;
    $cosB2 = 0;
    $hav = 0;
    $sum = 0;

    $lat = [];
    $lng = [];

    for ($i = 0; $i < count($path); $i++) {
        array_push($lat, $path[$i][0] * pi() / 180);
        array_push($lng, $path[$i][1] * pi() / 180);
    }

    for ($j = 0; $j < count($lat); $j++) {
        $k = $j + 1;
        if ($j == 0) {
            $lam1 = $lng[$j];
            $beta1 = $lat[$j];
            $lam2 = $lng[$j + 1];
            $beta2 = $lat[$j + 1];
            $cosB1 = cos($beta1);
            $cosB2 = cos($beta2);
        } else {
            $k = ($j + 1) % count($lat);
            $lam1 = $lam2;
            $beta1 = $beta2;
            $lam2 = $lng[$k];
            $beta2 = $lat[$k];
            $cosB1 = $cosB2;
            $cosB2 = cos($beta2);
        }

        if ($lam1 != $lam2) {
            $hav = ((1.0 - cos($beta2 - $beta1)) / 2.0) +
                    $cosB1 * $cosB2 * ((1.0 - cos($lam2 - $lam1)) / 2.0);
            $a = 2 * asin(sqrt($hav));
            $b = pi() / 2 - $beta2;
            $c = pi() / 2 - $beta1;
            $s = 0.5 * ($a + $b + $c);
            $t = tan($s / 2) * tan(($s - $a) / 2) *
                    tan(($s - $b) / 2) * tan(($s - $c) / 2);

            $excess = abs(4 * atan(sqrt(abs($t))));

            if ($lam2 < $lam1) {
                $excess = -$excess;
            }
            $sum += $excess;
        }
    }
    return abs($sum) * $r * $r;
}

/**
 * Detext whether the two given lines intersect.
 * 
 * @param array[array[int]] $l0 [[$x0,$y0],[$x1,$y1]]
 * @param array[array[int]] $l1 [[$x0,$y0],[$x1,$y1]]
 * @return boolean
 */
function lines_intersect($l0, $l1) {

    $Ax = $l0[0][0];
    $Ay = $l0[0][1];
    $Bx = $l0[1][0];
    $By = $l0[1][1];
    $Cx = $l1[0][0];
    $Cy = $l1[0][1];
    $Dx = $l1[1][0];
    $Dy = $l1[1][1];

    if ($Ax == $Bx && $Ay == $By || $Cx == $Dx && $Cy == $Dy)
        return FALSE;

    $Bx -= $Ax;
    $By -= $Ay;
    $Cx -= $Ax;
    $Cy -= $Ay;
    $Dx -= $Ax;
    $Dy -= $Ay;

    $distAB = sqrt($Bx * $Bx + $By * $By);

    $theCos = $Bx / $distAB;
    $theSin = $By / $distAB;
    $newX = $Cx * $theCos + $Cy * $theSin;
    $Cy = $Cy * $theCos - $Cx * $theSin;
    $Cx = $newX;
    $newX = $Dx * $theCos + $Dy * $theSin;
    $Dy = $Dy * $theCos - $Dx * $theSin;
    $Dx = $newX;

    //  Fail if the lines are parallel.
    if ($Cy == $Dy)
        return FALSE;

    //  Fail if segment C-D doesn't cross line A-B.
    if ($Cy < 0 && $Dy < 0 || $Cy >= 0 && $Dy >= 0.)
        return FALSE;

    $ABpos = $Dx + ($Cx - $Dx) * $Dy / ($Dy - $Cy);

    if ($ABpos < 0 || $ABpos > $distAB)
        return FALSE;

    return TRUE;
}