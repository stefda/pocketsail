<?php

class Rectangle {

    public $x1;
    public $x2;
    public $y1;
    public $y2;

    public function __construct($x1, $y1, $x2, $y2) {
        $this->x1 = $x1;
        $this->x2 = $x2;
        $this->y1 = $y1;
        $this->y2 = $y2;
    }

    function overlaps(Rectangle $rect) {
        return $this->x1 < $rect->x2 && $this->x2 > $rect->x1 && $this->y1 < $rect->y2 && $this->y2 > $rect->y1;
    }

}

class Shape {

    private $rects = [];

    public function __construct() {
        $this->rects = func_get_args();
    }

    public function add_rect(Rectangle $rect) {
        $this->rects[] = $rect;
    }

    public function get_rects() {
        return $this->rects;
    }

    public function overlaps(Shape $shape) {
        $rects = $shape->get_rects();
        for ($i = 0; $i < count($this->rects); $i++) {
            for ($j = 0; $j < count($rects); $j++) {
                if ($this->rects[$i]->overlaps($rects[$j])) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

}

class Marker {

    public $ID;
    public $lat;
    public $lng;
    public $cat;
    public $sub;
    public $label;
    public $shapes;
    public $shape = 0;
    public $pos;

    public function __construct($ID, $lat, $lng, $cat, $sub, $label, $pos) {
        $this->ID = $ID;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->cat = $cat;
        $this->sub = $sub;
        $this->label = $label;
        $this->pos = $pos;
        $this->init_shapes();
    }

    public function init_shapes() {

        $pos = $this->pos;
        $small = 3;
        $large = 8;
        $width = imagettfbbox(10, 0, APPPATH . 'fonts/arial.ttf', $this->label)[4];

        // Minimized icon
        $r11 = new Rectangle($pos->x - $small, $pos->y - $small, $pos->x + $small, $pos->y + $small);
        $s1 = new Shape($r11);
        $this->shapes[] = $s1;

        // Icon alone
        $r21 = new Rectangle($pos->x - $large, $pos->y - $large, $pos->x + $large, $pos->y + $large);
        $s2 = new Shape($r21);
        $this->shapes[] = $s2;

        if ($this->label !== '') {
            // Worst with label
            $r31 = new Rectangle($pos->x - $large, $pos->y - $large, $pos->x + $large, $pos->y + $large);
            $r32 = new Rectangle($pos->x - 20, $pos->y - 25, $pos->x - 20 + $width, $pos->y - 9);
            $s3 = new Shape($r31, $r32);
            $this->shapes[] = $s3;
            // Second worst with label
            $r41 = new Rectangle($pos->x - $large, $pos->y - $large, $pos->x + $large, $pos->y + $large);
            $r42 = new Rectangle($pos->x - 20, $pos->y + 10, $pos->x - 20 + $width, $pos->y + 26);
            $s4 = new Shape($r41, $r42);
            $this->shapes[] = $s4;
            // Second best
            $r51 = new Rectangle($pos->x - $large - 3 - $width, $pos->y - $large, $pos->x + $large, $pos->y + $large);
            $s5 = new Shape($r51);
            $this->shapes[] = $s5;
            // Best
            $r61 = new Rectangle($pos->x - $large, $pos->y - $large, $pos->x + $large + 4 + $width, $pos->y + $large);
            $s6 = new Shape($r61);
            $this->shapes[] = $s6;

            // With label set shape ID to the best alternative.
            $this->shape = 5;
        }
        else {
            // If no label, set shape ID to icon without label.
            $this->shape = 1;
        }
    }
    
    public function degrade_shape() {
        if ($this->shape > 0) {
            $this->shape--;
        }
    }
    
    public function is_minimized() {
        return $this->shape === 0;
    }
    
    public function get_shape() {
        return $this->shapes[$this->shape];
    }
    
    public function overlaps(Marker $m) {
        $s1 = $this->get_shape();
        $s2 = $m->get_shape();
        return $s1->overlaps($s2);
    }
}