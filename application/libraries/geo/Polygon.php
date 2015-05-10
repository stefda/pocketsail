<?php

class Polygon extends GeoJSON implements JsonSerializable {

    public function __construct($coordinates) {
        parent::__construct('Polygon', $coordinates);
    }

    /**
     * Create Polygon from a GeoJSON "Polygon" object.
     * 
     * @param array $geoJson GeoJSON object
     * @returns Polygon
     */
    public static function from_geo_json($geoJson) {
        if (!isset($geoJson['type']) || !isset($geoJson['coordinates'])) {
            return NULL;
        }
        return new Polygon($geoJson['coordinates']);
    }

    /**
     * TODO: Implement parsing more than one linear ring!
     * 
     * @param string $wkt
     * @return Polygon
     */
    public static function from_wkt($wkt) {

        if ($wkt === NULL) {
            return NULL;
        }

        $matches = [];
        $coordinates = [];

        // Do initial matching
        if (preg_match("/POLYGON\((.*)\)/i", $wkt, $matches)) {
            preg_match_all("/\(([^()]*)\)/i", $matches[1], $matches);
        }

        // Matches array remains empty if nothing is matched
        if (count($matches) == 0) {
            return new Polygon([]);
        }

        foreach ($matches[1] AS $sRing) {
            $sPoints = explode(",", trim($sRing));
            $ring = [];
            foreach ($sPoints AS $sPoint) {
                $matches = [];
                preg_match("/ *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) */", $sPoint, $matches);
                if (count($matches) == 0) {
                    error('Polygon: WKT format error.');
                }
                $ring[] = [floatval($matches[1]), floatval($matches[3])];
            }

            $coordinates[] = $ring;
        }
        return new Polygon($coordinates);
    }

    /**
     * Get the coordinates array fo the i-th ring.
     * 
     * @param int $i Ring position
     * @return array
     */
    public function get_ring($i) {
        return $this->coordinates[$i];
    }

    /**
     * Number of linear rings in the polygon.
     * 
     * @returns int
     */
    public function size() {
        return count($this->coordinates);
    }

    /**
     * Get the n-th point of the i-th ring.
     * 
     * @param int $i Position of the ring in the polygon
     * @param int $n Position of the point in the i-th ring
     * @returns Point
     */
    public function get_point($i, $n) {

        if ($i > $this->size() - 1) {
            error("Accessing undefined liear ring with index '$i'");
        }

        if ($n > 1) {
            error("Accessing undefined position with index '$n'");
        }

        $position = $this->coordinates[$i][$n];
        return new Point($position[0], $position[1]);
    }

    /**
     * @return string
     */
    public function to_wkt() {
        $str = 'POLYGON(';
        $rings = $this->coordinates;
        $sRings = [];
        foreach ($rings AS $ring) {
            $sRings[] = '(';
            for ($i = 0; $i < count($ring); $i++) {
                $sRings[count($sRings) - 1] .= $ring[$i][0] . ' ' . $ring[$i][1];
                $sRings[count($sRings) - 1] .= $i < count($ring) - 1 ? ',' : '';
            }
            $sRings[count($sRings) - 1] .= ')';
        }
        $str .= implode(',', $sRings);
        return $str . ')';
    }

    public function __toString() {
        return $this->to_wkt();
    }

    public function jsonSerialize() {
        return $this->to_geo_json();
    }

}
