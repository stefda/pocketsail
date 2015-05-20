<?php

require_library('geo/GeoJSON');

class LatLngBounds extends GeoJSON implements JsonSerializable, CL_Serializable {

    public function __construct(LatLng $sw = NULL, LatLng $ne = NULL) {

        if ($sw === NULL) {
            $sw = new LatLng(90, 180);
            $ne = new LatLng(-90, -180);
        }

        if ($ne === NULL) {
            $ne = new LatLng($sw->lat(), $sw->lng());
        }

        parent::__construct('LatLngBounds', [[$sw->lng(), $sw->lat()], [$ne->lng(), $ne->lat()]]);
    }

    public static function from_geo_json($geoJson) {
        if (!isset($geoJson['type']) || !isset($geoJson['coordinates'])) {
            return NULL;
        }
        $coordinates = $geoJson['coordinates'];
        $sw = new LatLng($coordinates[0][1], $coordinates[0][0]);
        $ne = new LatLng($coordinates[1][1], $coordinates[1][0]);
        return new LatLngBounds($sw, $ne);
    }

    /**
     * @param Polygon $polygon
     * @return LatLngBounds
     */
    public static function from_polygon(Polygon $polygon) {

        $bounds = new LatLngBounds();

        if ($polygon->size() == 0) {
            return $bounds;
        }

        // Use first ring only
        $ring = $polygon->get_ring(0);

        // Extend bounds by all positions in ring, one by one
        foreach ($ring AS $position) {
            $bounds->extend(new LatLng($position[1], $position[0]));
        }

        return $bounds;
    }

    public static function from_dimensions($width, $height, LatLng $latLng, $zoom) {

        $center = Proj::latlng2pixel($latLng, $zoom);

        $topRight = new Point($center->x() + $width / 2, $center->y() - $height / 2);
        $bottomLeft = new Point($center->x() - $width / 2, $center->y() + $height / 2);

        $ne = Proj::pixel2latLng($topRight, $zoom);
        $sw = Proj::pixel2latLng($bottomLeft, $zoom);

        return new LatLngBounds($sw, $ne);
    }

    /**
     * Extends the bounds by given coordinates. Corners remain unchanged if the
     * coordinates are within the existing bounds.
     * 
     * @param LatLng $latLng Coordinates to extend the bounds
     */
    public function extend(LatLng $latLng) {

        $lat = $latLng->lat();
        $lng = $latLng->lng();

        $north = $this->coordinates[1][1];
        $east = $this->coordinates[1][0];
        $south = $this->coordinates[0][1];
        $west = $this->coordinates[0][0];

        if ($north < $south) {
            $north = $south = $lat;
        } else {
            $lat < $south ? $south = $lat : $lat > $north && $north = $lat;
        }

        if (360 === $west - $east) {
            $west = $east = $lng;
        } else {
            if (!$this->east_west_contains_lng($east, $west, $lng)) {
                if ($this->wrap_lng($lng, $west) < $this->wrap_lng($east, $lng)) {
                    $west = $lng;
                } else {
                    $east = $lng;
                }
            }
        }

        $this->coordinates[1][1] = $north;
        $this->coordinates[1][0] = $east;
        $this->coordinates[0][1] = $south;
        $this->coordinates[0][0] = $west;
    }

    public function expand($zoom, $top, $right = NULL, $left = NULL, $bottom = NULL) {

        if ($bottom === NULL) {
            if ($left === NULL) {
                if ($right === NULL) {
                    $right = $left = $bottom = $top;
                } else {
                    $left = $right;
                    $bottom = $top;
                }
            } else {
                $bottom = $top;
            }
        }

        $sw = $this->get_south_west();
        $ne = $this->get_north_east();

        $bottomLeft = Proj::latlng2pixel($sw, $zoom);
        $topRight = Proj::latlng2pixel($ne, $zoom);

        // Expand
        $bottomLeft = new Point($bottomLeft->x() - $left, $bottomLeft->y() + $bottom);
        $topRight = new Point($topRight->x() + $right, $topRight->y() - $top);

        $this->set_south_west(Proj::pixel2latLng($bottomLeft, $zoom));
        $this->set_north_east(Proj::pixel2latLng($topRight, $zoom));
    }
    
    public function grow($d) {
        $ne = $this->get_north_east();
        $sw = $this->get_south_west();
        $this->set_north_east(geo_proximity($ne->lat(), $ne->lng(), $d, 45));
        $this->set_south_west(geo_proximity($sw->lat(), $sw->lng(), $d, 225));
    }

    private function wrap_lng($a, $b) {
        $c = $b - $a;
        return 0 <= $c ? $c : $b + 180 - ($a - 180);
    }

    private function east_west_contains_lng($east, $west, $lng) {
        -180 == $lng && ($lng = 180);
        return $west > $east ? ($lng >= $west || $lng <= $east) && !(360 === $west - $east) : $lng >= $west && $lng <= $east;
    }

    public function get_north_east() {
        return new LatLng($this->coordinates[1][1], $this->coordinates[1][0]);
    }

    public function get_south_west() {
        return new LatLng($this->coordinates[0][1], $this->coordinates[0][0]);
    }

    private function set_north_east(LatLng $ne) {
        $this->coordinates[1][1] = $ne->lat();
        $this->coordinates[1][0] = $ne->lng();
    }

    private function set_south_west(LatLng $sw) {
        $this->coordinates[0][1] = $sw->lat();
        $this->coordinates[0][0] = $sw->lng();
    }

    /**
     * Get north edge latitude.
     * 
     * @return float
     */
    public function get_north() {
        return $this->coordinates[1][1];
    }

    /**
     * Get east edge latitude.
     * 
     * @return float
     */
    public function get_east() {
        return $this->coordinates[1][0];
    }

    /**
     * Get south edge latitude.
     * 
     * @return float
     */
    public function get_south() {
        return $this->coordinates[0][1];
    }

    /**
     * Get west edge longitude.
     * 
     * @return float
     */
    public function get_west() {
        return $this->coordinates[0][0];
    }

    /**
     * Compute the center of the bounds.
     * 
     * @return LatLng
     */
    public function get_center() {

        $bottomLeft = Proj::latlng2merc($this->get_south_west());
        $topRight = Proj::latlng2merc($this->get_north_east());

        $x = $bottomLeft->x() > $topRight->x() ?
                ($bottomLeft->x() + $topRight->x() + M_PI * 2) / 2 :
                ($bottomLeft->x() + $topRight->x()) / 2;
        $y = ($bottomLeft->y() + $topRight->y()) / 2;

        return Proj::merc2latLng(new Point($x, $y));
    }

    /**
     * Computes the maximum integer zoom that will accomodate the bounds within
     * the given mercator map dimensions.
     * 
     * @example
     * $bounds = new LatLngBounds(new LatLng(0, 0), new LatLng(1, 1));
     * $zoom = bounds->get_max_zoom(800, 600, 10, 20);
     * 
     * @param int $width Map width in pixels
     * @param int $height Map height in pixels
     * @param int [$top = 0] Top padding (0 padding used if ommited)
     * @param int [$right = 0] Right padding (top padding used if ommited)
     * @param int [$bottom = 0] Bottom padding (top padding used if ommited)
     * @param int [$left = 0] Left padding (right padding used if ommited)
     * @returns int
     */
    public function get_max_zoom($width, $height, $top = 0, $right = 0, $bottom = 0, $left = 0) {

        // Subtract padding
        $width -= $right + $left;
        $height -= $top + $bottom;

        $sw = $this->get_south_west();
        $ne = $this->get_north_east();

        // Project onto the mercator plane
        $bottomLeft = Proj::latlng2merc($sw);
        $topRight = Proj::latlng2merc($ne);

        // Extend right x coordinate beyond antimeridian if needed
        $x_right = $bottomLeft->x() > $topRight->x() ? $topRight->x() + M_PI * 2 : $topRight->x();

        if ($bottomLeft->x() != $topRight->x()) {
            $z_x = log((GEO_2_PI * $width) / (GEO_TILE_SIZE * abs($bottomLeft->x() - $x_right))) / log(2);
        } else {
            // East = west, nothing to compute
            $z_x = Proj::ZOOMMAX;
        }

        if ($bottomLeft->y() != $topRight->y()) {
            $z_y = log((GEO_2_Y_LIM * $height) / (GEO_TILE_SIZE * abs($bottomLeft->y() - $topRight->y()))) / log(2);
        } else {
            // North = south, nothing to compute
            $z_y = Proj::ZOOMMAX;
        }

        $zf = pow(10, GEO_ZOOMPREC);
        $z_x = round($z_x * $zf) / $zf;
        $z_y = round($z_y * $zf) / $zf;

        return max(0, floor(min($z_x, $z_y)));
    }

    public function jsonSerialize() {
        return $this->to_geo_json();
    }

    public function __toString() {
        return 'LATLNGBOUNDS((' .
                implode(' ', array_reverse($this->coordinates[0])) . '), (' .
                implode(' ', array_reverse($this->coordinates[1])) . '))';
    }

    public static function deserialize($data) {
        $geoJson = deserialize($data, [
            'type' => FILTER_SANITIZE_STRING,
            'coordinates' => [
                'filter' => FILTER_VALIDATE_FLOAT,
                'flags' => FILTER_REQUIRE_ARRAY
            ]
        ]);
        return self::from_geo_json($geoJson);
    }

}
