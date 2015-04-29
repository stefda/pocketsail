<?php

/**
 * Normal bounds constrained by the [90,180,-90,-180] bounding box. The coor-
 * dinates are normalised in the constructor to fit these constraints.
 */
class Bounds {

    protected $s;
    protected $w;
    protected $n;
    protected $e;

    public function __construct(LatLng $sw, LatLng $ne) {

        $s = $sw->lat();
        $w = $sw->lng();
        $n = $ne->lat();
        $e = $ne->lng();

        // Wrap latitudes around the globe
        $s = Geo::wrapLat($s);
        $n = Geo::wrapLat($n);

        // Compute difference in longitude extremities
        $lngDiff = $e - $w;

        // Compute new longitudes
        if ($lngDiff >= 360) {
            $e = 180;
            $w = -180;
        } else {
            $e = Geo::wrapLng($e);
            $w = Geo::wrapLng($w);
        }

        // Assign bounds' attributes
        $this->s = $s;
        $this->w = $w;
        $this->n = $n;
        $this->e = $e;
    }

    public function s() {
        return $this->s;
    }

    public function w() {
        return $this->w;
    }

    public function n() {
        return $this->n;
    }

    public function e() {
        return $this->e;
    }

    /**
     * @return LatLng The south-west corner of the bounds
     */
    public function getSouthWest() {
        return new LatLng($this->s, $this->w);
    }

    /**
     * @return LatLng The north-east corner of the bounds
     */
    public function getNorthEast() {
        return new LatLng($this->n, $this->e);
    }

    public function getMaxZoom($width, $height, $top = 0, $right = FALSE, $bottom = FALSE, $left = FALSE) {

        // Compute padding
        if (!$left) {
            if (!$bottom) {
                if (!$right) {
                    $left = $bottom = $right = $top = !$top ? 0 : $top;
                } else {
                    $bottom = $top;
                    $left = $right;
                }
            } else {
                $left = $right;
            }
        }

        // Subtract padding
        $width -= $right + $left;
        $height -= $top + $bottom;

        // Project onto the mercator plane
        $A = Geo::mercator(new LatLng($this->s, $this->w));
        $B = Geo::mercator(new LatLng($this->n, $this->e));

        // Compute the zoom for both directions
        $z_x = log((GEO_2_PI * $width) / (GEO_TILE_SIZE * abs($A->x() - $B->x()))) / log(2);
        $z_y = log((GEO_2_Y_LIM * $height) / (GEO_TILE_SIZE * abs($A->y() - $B->y()))) / log(2);

        $zf = pow(10, GEO_ZOOMPREC);
        $z_x = round($z_x * $zf) / $zf;
        $z_y = round($z_y * $zf) / $zf;

        return max(0, floor(min($z_x, $z_y)));
    }

    public static function getBounds($width, $height, $zoom, LatLng $latLng) {

        $center = Proj::latlng2pixel($latLng, $zoom);

        $topRight = new Point($center->x() + $width / 2, $center->y() - $height / 2);
        $bottomLeft = new Point($center->x() - $width / 2, $center->y() + $height / 2);

        $ne = Proj::pixel2latLng($topRight, $zoom);
        $sw = Proj::pixel2latLng($bottomLeft, $zoom);

        return new Bounds($sw, $ne);
    }

    public static function fromPolygon(Polygon $polygon) {

        $points = $polygon->points();

        // Initialise extremes with first point's coordinates
        $n = $points[0]->y();
        $e = $points[0]->x();
        $s = $points[0]->y();
        $w = $points[0]->x();

        // Extend the bounds correspondingly
        foreach ($points AS $point) {
            $n = max($n, $point->y());
            $e = max($e, $point->x());
            $s = min($s, $point->y());
            $w = min($w, $point->x());
        }

        // Instantiate ViewBounds accordingly, then return
        return new ViewBounds(new LatLng($s, $w), new LatLng($n, $e));
    }

    public function getCenter() {

        $yDiff = merLat($this->n) - merLat($this->s);
        $lngDiff = $this->e - $this->w;

        $lat = meriLat(merLat($this->n) - $yDiff / 2);
        $lng = $this->e - $lngDiff / 2;

        return new LatLng($lat, $lng);
    }

    public function __toString() {
        return "Bounds [ne=($this->n,$this->e),sw=($this->s,$this->w)]";
    }

}
