<?php

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

}
