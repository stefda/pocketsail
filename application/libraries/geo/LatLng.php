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
