<?php

class LineString implements JsonSerializable {

    public $points;

    public function __construct($points = []) {
        $this->points = $points;
    }

    /**
     * @param string $wkt A well formed WKT representation of a LineString.
     * @return LineString|NULL Returns a LineString or NULL if WKT parsing
     * fails.
     */
    public static function fromWKT($wkt) {

        $matches = [];
        $points = [];

        // Do initial matching
        preg_match("/LINESTRING *\((.*)\)/i", $wkt, $matches);

        // Matches array remains empty if nothing is matched
        if (count($matches) == 0) {
            return NULL;
        }

        // Explode by comma matched coordinates trimmed off of spaces
        $sPoints = explode(",", trim($matches[1]));

        // If points are fewer than 2 the LineString is actually a point...
        if (count($sPoints) < 2) {
            return NULL;
        }

        // Iterate over coordinates to instantiate Points of the LineString
        foreach ($sPoints AS $sPoint) {
            $matches = [];
            // Parse xy point coordinates
            preg_match("/ *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) */", $sPoint, $matches);
            // Return null if matching fails
            if (count($matches) == 0) {
                return NULL;
            }
            // Instantiate next LineString point from matched coordinates
            $points[] = new Point($matches[1], $matches[3]);
        }

        // Uff, hard work that was!
        return new LineString($points);
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
     * @return int The number of coordinates in the LineString.
     */
    public function size() {
        return count($this->points);
    }

    public function toWKT() {
        $str = "LINESTRING(";
        for ($i = 0; $i < count($this->points); $i++) {
            $str .= $this->points[$i]->x . ' ' . $this->points[$i]->y;
            $str .= $i != count($this->points) - 1 ? "," : "";
        }
        $str .= ")";
        return $str;
    }

    public function jsonSerialize() {
        return $this->toWKT();
    }

    public function __toString() {
        return "LineString [size={$this->size()}]";
    }

}
