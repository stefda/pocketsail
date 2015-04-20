<?php

define('GEO_R', 6371);
define('GEO_TILE_SIZE', 256);
define('GEO_2_PI', 2 * M_PI);

function merLat($lat) {
    return Geo::mercatorLat($lat);
}

function merLng($lng) {
    return Geo::mercatorLng($lng);
}

function meriLat($y) {
    return Geo::mercatorLatInv($y);
}

function meriLng($x) {
    return Geo::mercatorLngInv($x);
}

function mergLat($lat, $zoom) {
    $f = GEO_TILE_SIZE * pow(2, $zoom);
    return (Geo::mercatorLat($lat) + M_PI) / GEO_2_PI * $f;
}

function mergLng($lng, $zoom) {
    $f = GEO_TILE_SIZE * pow(2, $zoom);
    return (Geo::mercatorLng($lng) + M_PI) / GEO_2_PI * $f;
}

function mergiLat($lat, $zoom) {
    $f = GEO_TILE_SIZE * pow(2, $zoom);
    return Geo::mercatorLatInv(($lat) / $f * GEO_2_PI - M_PI);
}

function mergiLng($lng, $zoom) {
    $f = GEO_TILE_SIZE * pow(2, $zoom);
    return Geo::mercatorLngInv(($lng) / $f * GEO_2_PI - M_PI);
}

/**
 * 
 */
class Geo {

    public static function wrapLat($lat) {
        return rad2deg(atan(sin(deg2rad($lat)) / abs(cos(deg2rad($lat)))));
    }

    public static function wrapLng($lng) {
        return rad2deg(atan2(sin(deg2rad($lng)), cos(deg2rad($lng))));
    }

    public static function mercator(LatLng $latLng) {

        $halfPI = M_PI / 2;

        $lat = $latLng->lat();
        $lng = $latLng->lng();

        $lat = $lat > 90 ? 90 : ($lat < -90 ? -90 : $lat);
        $lng = $lng > 180 ? 180 : ($lng < -180 ? -180 : $lng);

        $lam = deg2rad($lng);
        $phi = deg2rad($lat);

        $x = $lam;
        $y = log(tan(0.5 * ($phi + $halfPI)));

        return new Point($x, $y);
    }
    
    public static function latlng2meters(LatLng $latLng) {
        $p = self::mercator($latLng);
        $m = 20037508.34;
        return new Point($p->x() / pi() * $m, $p->y() / pi() * $m);
    }

    public static function mercatorLat($lat) {
        $lat = $lat > 90 ? 90 : ($lat < -90 ? -90 : $lat);
        $phi = deg2rad($lat);
        return log(tan(0.5 * ($phi + M_PI / 2)));
    }

    public static function mercatorLng($lng) {
        $lng = $lng > 180 ? 180 : ($lng < -180 ? -180 : $lng);
        return deg2rad($lng);
    }

    public static function mercatorInv(Point $point) {
        $lng = rad2deg($point->x);
        $lat = rad2deg(atan(pow(M_E, $point->y)) / 0.5 - M_PI / 2);
        return new LatLng($lat, $lng);
    }

    public static function mercatorLatInv($y) {
        return rad2deg(atan(pow(M_E, $y)) / 0.5 - M_PI / 2);
    }

    public static function mercatorLngInv($x) {
        return rad2deg($x);
    }

    /**
     * Computes distance between the given coordinates.
     * 
     * @param LatLng $latLngA
     * @param Point $latLngB
     * @return float
     */
    public static function haversine(LatLng $latLngA, LatLng $latLngB) {

        $latA = $latLngA->lat();
        $lngA = $latLngA->lng();
        $latB = $latLngB->lat();
        $lngB = $latLngB->lng();

        $dLat = deg2rad($latB - $latA);
        $dLng = deg2rad($lngB - $lngA);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latA)) * cos(deg2rad($latB)) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = GEO_R * $c;

        return $d;
    }

    /**
     * Computes the coordinates of a point at the distance $d and in the
     * direction $dir from the given coordinates.
     * 
     * @param LatLng $latlng
     * @param type $d Distance in km.
     * @param type $dir Diretion in degrees.
     * @return \LatLng
     */
    public static function proximity(LatLng $latlng, $d, $dir) {

        $lat = deg2rad($latlng->lat());
        $lng = deg2rad($latlng->lng());
        $dir = deg2rad($dir);

        $lat = asin(sin($lat) * cos($d / GEO_R) + cos($lat) * sin($d / GEO_R) * cos($dir));
        $lng = $lng + atan2(sin($dir) * sin($d / GEO_R) * cos($lat), cos($d / GEO_R) - sin($lat) * sin($lat));

        return new LatLng(rad2deg($lat), rad2deg($lng));
    }

    public static function area(Polygon $polygon) {

        $points = $polygon->points();
        
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

        for ($i = 0; $i < count($points); $i++) {
            array_push($lat, $points[$i]->y() * pi() / 180);
            array_push($lng, $points[$i]->x() * pi() / 180);
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

}
