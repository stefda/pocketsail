<?php

define('R', 6378);

class Geo {

    /**
     * @param LatLng $latLngA
     * @param Point $latLngB
     * @return LatLng
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
        $d = R * $c;

        return $d;
    }
    
    /**
     * @param type $n
     * @param type $e
     * @param type $s
     * @param type $w
     * @return Polygon
     */
    public static function create_bbox($n, $e, $s, $w) {
        $ne = new LatLng($n, $e);
        $se = new LatLng($s, $e);
        $sw = new LatLng($s, $w);
        $nw = new LatLng($n, $w);
        return new Polygon([$ne->to_point(), $se->to_point(), $sw->to_point(), $nw->to_point(), $ne->to_point()]);
    }

    public static function polygon_to_bbox(Polygon $poly, $d = 0) {

        $latMax = -90;
        $latMin = 90;
        $lngMax = -180;
        $lngMin = 180;

        foreach ($poly->get_points() AS $p) {
            $latMax = $p[0] > $latMax ? $p[0] : $latMax;
            $latMin = $p[0] < $latMin ? $p[0] : $latMin;
            $lngMax = $p[1] > $lngMax ? $p[1] : $lngMax;
            $lngMin = $p[1] < $lngMin ? $p[1] : $lngMin;
        }

        if ($d != 0) {
            $max = self::proximity($latMax, $lngMax, 1, 45);
            $min = self::proximity($latMin, $lngMin, 1, 225);
            $latMax = $max->lat;
            $lngMax = $max->lng;
            $latMin = $min->lat;
            $lngMin = $min->lng;
        }

        return new Polygon([[$latMax, $lngMin], [$latMin, $lngMin], [$latMin, $lngMax], [$latMax, $lngMax], [$latMax, $lngMin]]);
    }

    public static function proximity($lat, $lng, $d, $dir) {
        $lat = deg2rad($lat);
        $lng = deg2rad($lng);
        $dir = deg2rad($dir);
        $newLat = asin(sin($lat) * cos($d / R) + cos($lat) * sin($d / R) * cos($dir));
        $newLng = $lng + atan2(sin($dir) * sin($d / R) * cos($lat), cos($d / R) - sin($lat) * sin($newLat));
        $dLat = rad2deg($newLat);
        $dLng = rad2deg($newLng);
        return new LatLng($dLat, $dLng);
    }

    public static function point_to_bbox(LatLng $latLng, $d) {
        $points = [];
        $lat = $latLng->lat();
        $lng = $latLng->lng();
        $points[0] = self::proximity($lat, $lng, $d, 45);
        $points[1] = self::proximity($lat, $lng, $d, 135);
        $points[2] = self::proximity($lat, $lng, $d, 225);
        $points[3] = self::proximity($lat, $lng, $d, 315);
        $points[4] = $points[0];
        return new Polygon($points);
    }
    
    public static function latlng_to_bounds(LatLng $latLng, $d) {
        $lat = $latLng->lat;
        $lng = $latLng->lng;
        $ne = self::proximity($lat, $lng, $d, 45);
        $sw = self::proximity($lat, $lng, $d, 225);
        return new Bounds($ne->lat, $ne->lng, $sw->lat, $sw->lng);
    }

    public static function point_in_bbox(Polygon $bbox, Point $point) {
        $min = $bbox->get_point_at(1);
        $max = $bbox->get_point_at(3);
        return ($min->lat() < $point->lat() && $max->lat() > $point->lat() && $min->lng() < $point->lng() && $max->lng() > $point->lng());
    }

    private static function merc_y($lat) {
        if ($lat > 89.5) {
            $lat = 89.5;
        }
        if ($lat < -89.5) {
            $lat = -89.5;
        }
        $r_major = 6378137.0000;
        $r_minor = 6356752.3142;
        $temp = $r_minor / $r_major;
        $es = 1.0 - ($temp * $temp);
        $eccent = sqrt($es);
        $phi = deg2rad($lat);
        $sinphi = sin($phi);
        $con = $eccent * $sinphi;
        $com = 0.5 * $eccent;
        $con = pow((1.0 - $con) / (1.0 + $con), $com);
        $ts = tan(0.5 * ((M_PI * 0.5) - $phi)) / $con;
        $y = - $r_major * log($ts);
        return $y;
    }

    public static function merc_x($lng) {
        $rMajor = 6378137.0000;
        return $rMajor * deg2rad($lng);
    }

    public static function merc($lat, $lng) {
        return (object) ['x' => self::merc_x($lng), 'y' => self::merc_y($lat)];
    }

    public static function mercator($lat, $lng, $zoom) {

        $xMax = 40075016.685578;
        $yMax = 69238578.743712;
        $xMax2 = 20037508.342789;
        $yMax2 = 34619289.371856;
        $tileSize = 256;

        $merc = self::merc($lat, $lng);
        $x = $merc->x + $xMax2;
        $y = $yMax2 - $merc->y;

        return (object) [
                    'x' => round(($x / $xMax) * ($tileSize * pow(2, $zoom))),
                    'y' => round(($y / $yMax) * ($tileSize * pow(2, $zoom)))
        ];
    }

}
