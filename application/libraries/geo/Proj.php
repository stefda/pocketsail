<?php

require_library('geo/GeoJSON');
require_library('geo/Point');
require_library('geo/LatLng');

class Proj {

    const TILESIZE = 256;
    const ZOOMPREC = 3;
    const YLIM = 3.13130133147;
    const DOUBLEYLIM = 6.26260266294;
    const ZOOMMAX = 24;

    public static function latlng2merc(LatLng $latLng) {

        $lat = $latLng->lat();
        $lng = $latLng->lng();

        $lat = $lat > 85 ? 85 : ($lat < -85 ? -85 : $lat);
        $lng = $lng > 180 ? 180 : ($lng < -180 ? -180 : $lng);

        $lam = deg2rad($lng);
        $phi = deg2rad(-$lat);

        $x = $lam;
        $y = -1 * log((tan(0.5 * (M_PI_2 - $phi))));

        return new Point($x, $y);
    }

    public static function latlng2pixel(LatLng $latLng, $zoom) {

        $point = self::latlng2merc($latLng);
        $scale = Proj::TILESIZE * pow(2, $zoom);

        $x = round(($point->x() + M_PI) / (M_PI * 2) * $scale);
        $y = round(($point->y() + Proj::YLIM) / Proj::DOUBLEYLIM * $scale);

        return new Point($x, $y);
    }

    public static function merc2latLng(Point $point) {

        $lng = rad2deg($point->x());
        $lat = rad2deg(-(atan(pow(M_E, $point->y())) / 0.5 - M_PI_2));

        return new LatLng($lat, $lng);
    }

    public static function pixel2latLng(Point $point, $zoom) {

        $scale = Proj::TILESIZE * pow(2, $zoom);

        $x = $point->x() / $scale * M_PI * 2 - M_PI;
        $y = $point->y() / $scale * Proj::DOUBLEYLIM - Proj::YLIM;

        return self::merc2latLng(new Point($x, $y));
    }

}
