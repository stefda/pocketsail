<?php

class Point implements JsonSerializable {

    protected $x;
    protected $y;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param string $wkt
     * @return Point|null
     */
    public static function fromWKT($wkt) {

        $matches = [];

        // Do matching
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
     * @return double
     */
    public function x() {
        return $this->x;
    }
    
    /**
     * @return double
     */
    public function y() {
        return $this->y;
    }

    /**
     * @param Point $point
     * @return boolean
     */
    public function equals(Point $point) {
        return $this->x() == $point->x() && $this->y() == $point->y();
    }

    /**
     * @return string
     */
    public function toWKT() {
        return "POINT($this->x $this->y)";
    }

    public function jsonSerialize() {
        return $this->toWKT();
    }

    public function __toString() {
        return "Point [coords=($this->x,$this->y)]";
    }

}
