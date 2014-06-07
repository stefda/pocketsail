<?php

class ViewBounds extends Bounds implements JsonSerializable {

    public $s;
    public $w;
    public $n;
    public $e;

    public function __construct($sw = NULL, $ne = NULL) {
        if ($sw === NULL) {
            $this->s = NULL;
            $this->w = NULL;
            $this->n = NULL;
            $this->e = NULL;
        } else {
            $this->s = $sw->lat();
            $this->w = $sw->lng();
            $this->n = $ne->lat();
            $this->e = $ne->lng();
        }
    }

    /**
     * @param string $wkt
     * @return ViewBounds
     */
    public static function fromWKT($wkt) {

        $matches = [];

        // Do matching
        preg_match("/BOUNDS *\( *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) *, *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) *\)/i", $wkt, $matches);

        // Matches array remains empty if nothing is matched
        if (count($matches) == 0) {
            return NULL;
        }

        // Extract point coordinates from matches
        $w = $matches[1];
        $s = $matches[3];
        $e = $matches[5];
        $n = $matches[7];

        // Initialise bounds' corners
        $sw = new LatLng($s, $w);
        $ne = new LatLng($n, $e);

        return new ViewBounds($sw, $ne);
    }

    /**
     * Expects a polygon that is "unwrapped", i.e. if the polygon should cross
     * the antimeridian, its longitude coordinates are appropriately extended
     * to a coordinate system stretching from -360° west to 360° east.
     * 
     * @param Polygon $polygon
     * @return ViewBounds
     */
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

    public function extendByLatLng(LatLng $latLng) {
        if ($this->s === NULL) {
            $this->s = $latLng->lat();
            $this->w = $latLng->lng();
            $this->n = $latLng->lat();
            $this->e = $latLng->lng();
        } else {
            $this->s = min($this->s, $latLng->lat());
            $this->w = min($this->w, $latLng->lng());
            $this->n = max($this->n, $latLng->lat());
            $this->e = max($this->e, $latLng->lng());
        }
    }

    public function extendByBounds(ViewBounds $bounds) {
        if ($this->s === NULL) {
            $this->s = $bounds->s;
            $this->w = $bounds->w;
            $this->n = $bounds->n;
            $this->e = $bounds->e;
        } else {
            $this->s = min($this->s, $bounds->s);
            $this->w = min($this->w, $bounds->w);
            $this->n = max($this->n, $bounds->n);
            $this->e = max($this->e, $bounds->e);
        }
    }

    public function buffer($x, $y, $zoom) {

        $f = GEO_TILE_SIZE * pow(2, $zoom);

        $s_g = (Geo::mercatorLat($this->s) + M_PI) / GEO_2_PI * $f;
        $w_g = (Geo::mercatorLng($this->w) + M_PI) / GEO_2_PI * $f;
        $n_g = (Geo::mercatorLat($this->n) + M_PI) / GEO_2_PI * $f;
        $e_g = (Geo::mercatorLng($this->e) + M_PI) / GEO_2_PI * $f;

        $s_g -= $y;
        $w_g -= $x;
        $n_g += $y;
        $e_g += $x;

        $this->s = Geo::mercatorLatInv(($s_g - $y) / $f * GEO_2_PI - M_PI);
        $this->w = Geo::mercatorLngInv(($w_g - $x) / $f * GEO_2_PI - M_PI);
        $this->n = Geo::mercatorLatInv(($n_g + $y) / $f * GEO_2_PI - M_PI);
        $this->e = Geo::mercatorLngInv(($e_g + $x) / $f * GEO_2_PI - M_PI);
    }

    public function __clone() {
        return new ViewBounds(new LatLng($this->s, $this->w), new LatLng($this->n, $this->e));
    }

    /**
     * @return LatLng
     */
    public function getCenter() {

        $yN = Geo::mercatorLat($this->n);
        $yS = Geo::mercatorLat($this->s);
        $yDiff = $yN - $yS;
        $lat = Geo::mercatorLatInv($yN - $yDiff / 2);

        $ewDiff = $this->e - $this->w;
        $lng = $this->e - $ewDiff / 2;

        return new LatLng($lat, $lng);
    }

    /**
     * @param LatLng $center
     */
    public function setCenter(LatLng $center) {

        $y = Geo::mercatorLat($center->lat());
        $yN = Geo::mercatorLat($this->n);
        $yS = Geo::mercatorLat($this->s);

        $yDiff = $yN - $yS;
        $lngDiff = $this->e - $this->w;

        $this->n = Geo::mercatorLatInv($y + $yDiff / 2);
        $this->s = Geo::mercatorLatInv($y - $yDiff / 2);
        $this->e = $center->lng() + $lngDiff / 2;
        $this->w = $center->lng() - $lngDiff / 2;
    }

    /**
     * Changes bounds' zoom by the given $zoomDiff value. Positive values zoom
     * into the bounds, negative values zoom out.
     * 
     * @param int $zoomDiff
     */
    public function changeZoom($zoomDiff) {

        // Compute zooming factor
        $zoomFact = 1 - 1 / pow(2, $zoomDiff);

        // Compute latitude difference between east-west extremities
        $lngDiff = $this->e - $this->w;
        // Compute new east-west extremities
        $this->e -= $lngDiff / 2 * $zoomFact;
        $this->w += $lngDiff / 2 * $zoomFact;

        // Project latitudes onto a square
        $yN = Geo::mercatorLat($this->n);
        $yS = Geo::mercatorLat($this->s);

        // Compute projected differense between projects north and south
        $yDiff = $yN - $yS;

        // Compute new projected north and south
        $yN -= $yDiff / 2 * $zoomFact;
        $yS += $yDiff / 2 * $zoomFact;

        // Project back onto sphere and assign
        $this->n = Geo::mercatorLatInv($yN);
        $this->s = Geo::mercatorLatInv($yS);
    }

    public function zoomIn($zoomDiff = 1) {
        $this->changeZoom($zoomDiff);
    }

    public function zoomOut($zoomDiff = 1) {
        $this->changeZoom(-$zoomDiff);
    }

    /**
     * @param ViewBounds $bounds
     */
    public function fitBounds(ViewBounds $bounds, &$zoom = NULL) {

        // Set centre to given bounds centre
        $this->setCenter($bounds->getCenter());

        // Project this bounds' north and south latitudes using mercator
        $aN = Geo::mercatorLat($this->n);
        $aS = Geo::mercatorLat($this->s);
        $aLatDiff = $aN - $aS;
        // Do the same with given bounds
        $bN = Geo::mercatorLat($bounds->n());
        $bS = Geo::mercatorLat($bounds->s());
        $bLatDiff = $bN - $bS;

        // Compute meridian zoom difference
        $latZoomDiff = floor((log($aLatDiff) - log($bLatDiff)) / log(2) + 0.001); // Correct possible floating point error

        // Compute parallel differences for this and given bounds
        $aLngDiff = $this->e - $this->w;
        $bLngDiff = $bounds->e() - $bounds->w();

        // Compute parallel zoom difference
        $lngZoomDiff = floor((log($aLngDiff) - log($bLngDiff)) / log(2) + 0.001);  // Correct possible floating point error

        // Change zoom using smaller of the two computed zoom differences
        $zoomDiff = min($latZoomDiff, $lngZoomDiff);
        $this->changeZoom($zoomDiff);

        // Update zoom if given
        if ($zoom !== NULL) {
            $zoom += $zoomDiff;
        }
    }

    /**
     * @return \Bounds
     */
    public function toBounds() {
        return new Bounds(new LatLng($this->s, $this->w), new LatLng($this->n, $this->e));
    }

    /**
     * @return \Polygon
     */
    public function toPolygon() {
        $topRight = new Point($this->e, $this->n);
        $bottomRight = new Point($this->e, $this->s);
        $bottomLeft = new Point($this->w, $this->s);
        $topLeft = new Point($this->w, $this->n);
        $points = [$topRight, $bottomRight, $bottomLeft, $topLeft, $topRight];
        return new Polygon($points);
    }

    public function toWKT() {
        return "BOUNDS($this->w $this->s,$this->e $this->n)";
    }

    public function jsonSerialize() {
        return $this->toWKT();
    }

    public function __toString() {
        return "ViewBounds [sw=($this->s,$this->w),ne=($this->n,$this->e)]";
    }

}
