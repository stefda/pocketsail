<?php

class Polygon implements JsonSerializable {

    public $points;

    public function __construct($points = []) {
        $this->points = $points;
    }

    /**
     * @param string $wkt
     * @return Polygon|null
     */
    public static function fromWKT($wkt) {

        $matches = [];
        $points = [];

        // Do initial matching
        preg_match("/POLYGON *\( *\((.*)\).*\)/i", $wkt, $matches);

        // Matches array remains empty if nothing is matched
        if (count($matches) == 0) {
            return NULL;
        }

        // Explode by comma matched coordinates trimmed off of spaces
        $sPoints = explode(",", trim($matches[1]));

        // If points are fewer than 2 the LineString is actually a point...
        if (count($sPoints) < 3) {
            return NULL;
        }

        // Iterate over coordinates to instantiate Points of the Polygons
        foreach ($sPoints AS $sPoint) {
            $matches = [];
            // Parse xy point coordinates
            preg_match("/ *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) */", $sPoint, $matches);
            // Return null if matching fails
            if (count($matches) == 0) {
                return NULL;
            }
            // Instantiate next Polygon point from matched coordinates
            $points[] = new Point($matches[1], $matches[3]);
        }

        // Polygon's first and last coordinate must match
        if (!$points[0]->equals($points[count($points) - 1])) {
            return NULL;
        }

        // Finally, instantiate and return Polygon
        return new Polygon($points);
    }

    /**
     * @param int $i
     * @return Point|NULL
     */
    public function getPointAt($i) {
        if ($i < 0 || $i > count($this->points) - 1) {
            return NULL;
        }
        return $this->points[$i];
    }

    /**
     * @return int The number of coordinates in the Polygon.
     */
    public function size() {
        return count($this->points);
    }

    /**
     * @return string WKT representation of the Polygon.
     */
    public function toWKT() {
        $str = "POLYGON((";
        for ($i = 0; $i < count($this->points); $i++) {
            $str .= $this->points[$i]->x . " " . $this->points[$i]->y;
            $str .= $i < count($this->points) - 1 ? "," : "";
        }
        $str .= "))";
        return $str;
    }

    public function jsonSerialize() {
        return $this->toWKT();
    }

    public function __toString() {
        return "Polygon [size={$this->size()}]";
    }

}
