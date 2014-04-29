<?php

class Bounds {

    public $n;
    public $e;
    public $s;
    public $w;

    public function __construct($n, $e, $s, $w) {
        $this->n = $n;
        $this->e = $e;
        $this->s = $s;
        $this->w = $w;
    }

    public function contract() {
        $nsf = ($this->n - $this->s) / 4;
        $ewf = ($this->e > $this->w ? ($this->e - $this->w) : (360 + $this->e - $this->w)) / 4;
        $this->n -= $nsf;
        $this->s += $nsf;
        $this->e = ($this->e - $ewf) % -180;
        $this->w = ($this->w + $ewf) % 180;
    }

    public function expand() {
        $nsf = ($this->n - $this->s) / 2;
        $ewf = ($this->e > $this->w ? ($this->e - $this->w) : (360 + $this->e - $this->w)) / 2;
        $this->n += $nsf;
        $this->s -= $nsf;
        $this->e = ($this->e + $ewf) % 180;
        $this->w = ($this->w - $ewf) % -180;
    }

    public function inside(Bounds $bbox) {
        return $this->n >= $bbox->n && $this->s <= $bbox->s && $this->e >= $bbox->e && $this->w <= $bbox->w;
    }

    public function __toString() {
        return '[' . $this->n . ',' . $this->e . ',' . $this->s . ',' . $this->w . ']';
    }

}
