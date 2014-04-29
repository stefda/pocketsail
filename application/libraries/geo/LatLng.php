<?php

class LatLng implements JsonSerializable {

    public $lat;
    public $lng;

    public function __construct($lat, $lng) {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public static function from_WKT($wkt) {
        $res = NULL;
        preg_match('/Point\((.*) (.*)\)/i', $wkt, $res);
        if ($res === NULL || count($res) == 0) {
            return NULL;
        }
        $lat = $res[2];
        $lng = $res[1];
        return new LatLng($lat, $lng);
    }

    public function to_point() {
        return new Point($this->lng, $this->lat);
    }

    public function to_WKT() {
        return 'Point(' . $this->lng . ' ' . $this->lat . ')';
    }

    public static function deserialize($sLatLng) {
        return new LatLng($sLatLng['lat'], $sLatLng['lng']);
    }

    public function serialize() {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng
        ];
    }

    public static function wrap_lat($lat) {
        return rad2deg(atan(sin(deg2rad($lat)) / abs(cos(deg2rad($lat)))));
    }

    public static function wrap_lng($lng) {
        return rad2deg(atan2(sin(deg2rad($lng)), cos(deg2rad($lng))));
    }

    public function __toString() {
        return '[' . $this->lat . ', ' . $this->lng . ']';
    }
    
    public function toSQL() {
        $wkt = $this->to_WKT();
        return "GeomFromText('$wkt')";
    }

    public function jsonSerialize() {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng
        ];
    }

}
