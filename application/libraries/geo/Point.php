<?php

require_library('geo/GeoJSON');

class Point extends GeoJSON implements JsonSerializable {

    public function __construct($x, $y) {
        parent::__construct('Point', [$x, $y]);
    }

    /**
     * Create Point from a GeoJSON "Point" object.
     * 
     * @param array $geoJson GeoJSON object
     * @returns Point
     */
    public static function from_geo_json($geoJson) {
        return new Point($geoJson['coordinates'][0], $geoJson['coordinates'][1]);
    }

    /**
     * Create Point from a well known text string.
     * 
     * @param string $wkt Point in WKT
     * @returns Point
     */
    public static function from_wkt($wkt) {

        $matches = [];

        preg_match("/POINT *\( *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) *\)/i", $wkt, $matches);

        // Matches array remains empty if nothing is matched
        if (count($matches) == 0) {
            return NULL;
        }

        // Extract point coordinates from matches
        $x = $matches[1];
        $y = $matches[3];

        return new Point(floatval($x), floatval($y));
    }

    /**
     * @return float
     */
    public function x() {
        return $this->coordinates[0];
    }

    /**
     * @return float
     */
    public function y() {
        return $this->coordinates[1];
    }

    public function equals(Point $point) {
        return $this->x() === $point->x() && $this->y() === $point->y();
    }

    /**
     * @return string
     */
    public function to_wkt() {
        return "POINT({$this->x()} {$this->y()})";
    }
    
    public function __toString() {
        return $this->to_wkt();
    }

    public function jsonSerialize() {
        return $this->to_geo_json();
    }

}
