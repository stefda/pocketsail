<?php

class PlaceOrderer {

    private $places;

    public function __construct() {
        $this->places = [];
    }

    public function add_places($places) {
        foreach ($places AS $place) {
            $this->add_place($place);
        }
    }

    public function add_place(Place $place) {

        $i = 0;
        for (; $i < count($this->places); $i++) {
            if ($this->places[$i]->get_distance() > $place->get_distance()) {
                break;
            }
        }
        array_splice($this->places, $i, 0, [$place]);
    }

    public function get_places() {
        return $this->places;
    }

}

class PlaceAsorter {

    private $places;

    public function __construct() {
        $this->places = new stdClass();
    }

    public function add_places($places) {
        foreach ($places AS $place) {
            $this->add_place($place);
        }
    }

    public function add_place(Place $place) {

        $class = $place->get_class();
        $type = $place->get_type();

        if (!@$this->places->{$class}) {
            $this->places->{$class} = new stdClass();
        }
        if (!@$this->places->{$class}->{$type}) {
            $this->places->{$class}->{$type} = [];
        }
        $this->places->{$class}->{$type}[] = $place->to_object();
    }

    public function get_places() {
        return $this->places;
    }

}