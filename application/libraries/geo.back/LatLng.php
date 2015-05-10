<?php

require_library('geo/Point');

class LatLng extends Point implements JsonSerializable {

    public function __construct($lat, $lng) {
        parent::__construct($lng, $lat);
    }

    /**
     * @param string $wkt
     * @return LatLng|NULL
     */
    public static function fromWKT($wkt) {
        $point = Point::fromWKT($wkt);
        if ($point === NULL) {
            return NULL;
        }
        return new LatLng($point->y, $point->x);
    }

    public function lat() {
        return $this->y;
    }

    public function lng() {
        return $this->x;
    }
    
    public function latFormatted() {
        $lat = $this->y;
        $dir = $lat > 0 ? 'N' : 'S';
        $lat *= $lat > 0 ? 1 : -1;
        $deg = $lat % 90;
        $min = round(($lat - $deg) * 60, 2);
        $min = number_format($min, 2);
        return "$deg&deg; $min' $dir";
    }
    
    public function lngFormatted() {
        $lng = $this->x;
        $dir = $lng > 0 ? 'E' : 'W';
        $lng *= $lng > 0 ? 1 : -1;
        $deg = $lng % 180;
        $min = round(($lng - $deg) * 60, 2);
        $min = number_format($min, 2);
        return "0$deg&deg; $min' $dir";
    }

    /**
     * @return Point
     */
    public function toPoint() {
        return new Point($this->x, $this->y);
    }

    public function __toString() {
        return "LatLng [coords=($this->y,$this->x)]";
    }

}
