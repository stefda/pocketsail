<?php

class Polygon {
    
    private $coords;
    
    public function __construct($coords) {
        $this->coords = $coords;
    }
    
    public static function fromGeoJson($geoJson) {
        
        // Check if the GeoJSON type is Polygon
        if (strtolower($geoJson->type) != "polygon") {
            throw new Exception("Cannot construct Polygon from GeoJSON type '{$geoJson->type}'.");
        }
        
        // Check if coordinates are set
        if (!isset($geoJson->coordinates)) {
            throw new Exception("Cannot construct Polygon from GeoJSON without coordinates.");
        }
        
        return new Polygon($geoJson->coordinates);
    }
    
    public function toGeoJson() {
        return (object) [
            'type' => "Polygon",
            'coordinates' => $this->coords
        ];
    }
    
    public function __toString() {
        return json_encode($this->toGeoJson());
    }
}