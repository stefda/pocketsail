<?php

class Polygon implements JsonSerializable {

    private $points;

    public function __construct($points = []) {
        $this->points = $points;
    }

    public static function from_WKT($wkt) {
        $points = [];
        preg_match('/Polygon\(\((.*)\)\)/i', $wkt, $res);
        // NULL if nothing matched
        if ($res === NULL || count($res) == 0) {
            return NULL;
        }
        $strPoints = explode(",", $res[1]);
        if (count($strPoints) < 3) {
            return new Polygon();
        }
        foreach ($strPoints AS $strPoint) {
            $strPoint = explode(" ", $strPoint);
            $points[] = new Point($strPoint[0], $strPoint[1]);
        }
        return new Polygon($points);
    }

    /**
     * @param int $i
     * @return null|\Point
     */
    public function get_point_at($i) {
        if ($i < 0 || $i > count($this->points) - 1) {
            return NULL;
        }
        return new Point($this->points[$i][0], $this->points[$i][1]);
    }

    public function size() {
        return count($this->points);
    }

    public function get_points() {
        return $this->points;
    }

    public function to_WKT() {
        $string = 'POLYGON((';
        for ($i = 0; $i < count($this->points); $i++) {
            $string .= $this->points[$i]->x . ' ' . $this->points[$i]->y;
            $string .= $i < count($this->points) - 1 ? ',' : '';
        }
        $string .= '))';
        return $string;
    }
    
    public function serialize() {
        $sPoints = [];
        foreach ($this->points AS $point) {
            $sPoints[] = $point->serialize();
        }
        return $sPoints;
    }

    public static function deserialize($sPoints) {
        $points = [];
        for ($i = 0; $i < count($sPoints); $i++) {
            $points[] = Point::deserialize($sPoints[$i]);
        }
        return new Polygon($points);
    }
    
    public function toSQL() {
        $wkt = $this->to_WKT();
        return "GeomFromText('$wkt')";
    }

    public function jsonSerialize() {
        return array_slice($this->points, 0, count($this->points) - 1);
    }

}
