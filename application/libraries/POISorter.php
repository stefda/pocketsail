<?php

class POISorter {
    
    private $priority;
    private $pois;
    
    public function __construct($priority) {
        $this->priority = $priority;
        $this->pois = new stdClass();
        for ($i = 0; $i < count($priority); $i++) {
            $this->pois->{$priority[$i]} = [];
        }
    }
    
    public function add_poi($poi) {
        $a = $this->pois->{$poi->sub};
        $pos = 0;
        for (; $pos < count($a); $pos++) {
            if ($a[$pos]->rank < $poi->rank) {
                break;
            }
        }
        array_splice($this->pois->{$poi->sub}, $pos, 0, [$poi]);
    }
    
    public function get_pois() {
        $pois = [];
        foreach ($this->priority AS $sub) {
            $pois = array_merge($pois, $this->pois->{$sub});
        }
        return $pois;
    }
}

class POIModel {
    
    public $ID;
    public $lat;
    public $lng;
    public $cat;
    public $sub;
    public $label;
    public $rank;
    
    public function __construct($ID, $lat, $lng, $cat, $sub, $label, $rank) {
        $this->ID = $ID;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->cat = $cat;
        $this->sub = $sub;
        $this->label = $label;
        $this->rank = $rank;
    }
}
