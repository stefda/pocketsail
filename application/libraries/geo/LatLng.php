<?php

class LatLng implements JsonSerializable {

    public $lat;
    public $lng;

    public function __construct($lat, $lng) {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * @param string $wkt A well formed WKT representation of a Point.
     * @return LatLng|NULL Returns a LatLng or NULL if WKT parsing
     * fails.
     */
    public static function fromWKT($wkt) {
        $point = Point::fromWKT($wkt);
        if ($point === NULL) {
            return NULL;
        }
        return new LatLng($point->y, $point->x);
    }

    /**
     * @return Point
     */
    public function toPoint() {
        return new Point($this->lng, $this->lat);
    }

    public function toWKT() {
        return $this->toPoint()->toWKT();
    }

    public function jsonSerialize() {
        return $this->toWKT();
    }

    public function __toString() {
        return "LatLng [coords=($this->lat,$this->lng)]";
    }

}
