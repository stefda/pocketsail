<?php

class LatLng extends GeoJSON implements JsonSerializable {

    public function __construct($lat, $lng) {
        $lat = $lat > 90 ? 90 : ($lat < -90 ? -90 : $lat);
        $lng = $lng <= 180 ? $lng >= -180 ? $lng : 180 + fmod($lng - 180, 360) : -180 + fmod($lng + 180, 360);
        parent::__construct('Point', [$lng, $lat]);
    }

    /**
     * Create coordinates from a GeoJSON "Point" object.
     * 
     * @param array $geoJson GeoJSON object
     * @returns LatLng
     */
    public static function from_geo_json($geoJson) {
        if (!isset($geoJson['type']) || !isset($geoJson['coordinates'])) {
            return NULL;
        }
        return new LatLng($geoJson['coordinates'][1], $geoJson['coordinates'][0]);
    }

    /**
     * Create LatLng from a well known text string of a point.
     * 
     * @param string $wkt Point in WKT
     * @returns LatLng
     */
    public static function from_wkt($wkt) {
        $point = Point::from_wkt($wkt);
        return new LatLng($point->y(), $point->x());
    }

    /**
     * Get latitude.
     * 
     * @return float
     */
    public function lat() {
        return $this->coordinates[1];
    }

    /**
     * Get longitude.
     * 
     * @return float
     */
    public function lng() {
        return $this->coordinates[0];
    }
    
    public function latFormatted() {
        $lat = $this->coordinates[1];
        $dir = $lat > 0 ? 'N' : 'S';
        $lat *= $lat > 0 ? 1 : -1;
        $deg = $lat % 90;
        $min = round(($lat - $deg) * 60, 2);
        $min = number_format($min, 2);
        return "$deg&deg; $min' $dir";
    }
    
    public function lngFormatted() {
        $lng = $this->coordinates[0];
        $dir = $lng > 0 ? 'E' : 'W';
        $lng *= $lng > 0 ? 1 : -1;
        $deg = $lng % 180;
        $min = round(($lng - $deg) * 60, 2);
        $min = number_format($min, 2);
        return "0$deg&deg; $min' $dir";
    }

    /**
     * Get the coordinates as a Point object.
     * 
     * @return Point
     */
    public function to_point() {
        return new Point($this->coordinates[0], $this->coordinates[1]);
    }

    /**
     * @return string
     */
    public function to_wkt() {
        return "POINT({$this->lng()} {$this->lat()})";
    }

    public function __toString() {
        return 'LATLNG(' . $this->lat() . ' ' . $this->lng() . ')';
    }

    public function jsonSerialize() {
        return $this->to_geo_json();
    }

}
