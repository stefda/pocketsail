<?php

class Point {
    
    private $coords;
    
    public function __construct($coords) {
        $this->coords = $coords;
    }
    
    public static function fromGeoJson($geoJson) {
        
        // Check if the GeoJSON type is Point
        if (strtolower($geoJson->type) != "point") {
            throw new Exception("Cannot construct Point from GeoJSON type '{$geoJson->type}'.");
        }
        
        // Check if coordinates are set
        if (!isset($geoJson->coordinates)) {
            throw new Exception("Cannot construct Point from GeoJSON without coordinates.");
        }
        
        return new Point($geoJson->coordinates);
    }
    
    public function toGeoJson() {
        
        return (object) [
            'type' => "Point",
            'coordinates' => [$this->coords[0], $this->coords[1]]
        ];
    }
    
    public function __toString() {
        return json_encode($this->toGeoJson());
    }
}