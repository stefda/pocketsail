<?php

define('GEO_R', 6371);
define('GEO_TILE_SIZE', 256);
define('GEO_2_PI', 2 * M_PI);

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

}
