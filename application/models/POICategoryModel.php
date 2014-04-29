<?php

class POICategoryModel {
    
    private static $instance = NULL;
    
    public $all = [
        'geo' => [
            'cove', 'island', 'archipelago'
        ],
        'admin' => [
            'town', 'region', 'country'
        ],
        'berthing' => [
            'marina', 'harbour', 'yacht_club'
        ]
    ];
    
    public $map = [
        'geo' => 'geofraphical feature',
        'admin' => 'administrative area',
        'berthing' => 'berthing facility',
        'cove' => 'cove',
        'island' => 'island',
        'archipelago' => 'archipelago',
        'town' => 'town',
        'region' => 'region',
        'country' => 'country',
        'marina' => 'marina',
        'harbour' => 'harbour',
        'yacht_club' => 'yacht club'
    ];
    
    public $searchMap = [
        'marina' => 'marina',
        'gas_station' => 'gas station',
        'anchorage' => 'anchorage',
        'restaurant' => 'restaurant',
        'bar' => 'bar',
        'night_club' => 'night club'
    ];
    
    public $searchReverseMap = []; // Create from searchMap in constructor
    
    /**
     * @return POICategoryModel
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new POICategoryModel();
        }
        return self::$instance;
    }
    
    private function __construct() {
        foreach ($this->searchMap AS $sub => $term) {
            $this->searchReverseMap[$term] = $sub;
            $this->searchReverseMap[$term . 's'] = $sub;
        }
    }
}
