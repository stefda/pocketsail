<?php

class ViewBounds extends Bounds implements JsonSerializable {

    public $n;
    public $e;
    public $s;
    public $w;

    public function __construct($ne, $sw) {
        $this->n = $ne->lat;
        $this->e = $ne->lng;
        $this->s = $sw->lat;
        $this->w = $sw->lng;
    }

    /**
     * @param string $wkt
     * @return ViewBounds
     */
    public static function fromWKT($wkt) {

        // Start with using the wkt string to create a LineString
        $ls = LineString::fromWKT($wkt);

        // Return NULL if the LineString is NULL or doesn't containt exacly two
        // points
        if ($ls === NULL || $ls->size() !== 2) {
            return NULL;
        }

        // Assign the two points to bounds' corners
        $ne = $ls->getPointAt(0)->toLatLng();
        $sw = $ls->getPointAt(1)->toLatLng();

        // Return NULL if the point are wrongly coordinated
        if ($ne->lat < $sw->lat || $ne->lng < $sw->lng) {
            return NULL;
        }

        // Finally, return correctly initialised Bounds
        return new ViewBounds($ne, $sw);
    }
    
    public static function fromPolygon(Polygon $polygon) {
        
        $points = $polygon->points;
        
        // Initialise extremes with first point's coordinates
        $n = $points[0]->y;
        $e = $points[0]->x;
        $s = $points[0]->y;
        $w = $points[0]->x;
        
        // Extend the bounds correspondingly
        foreach ($points AS $point) {
            $n = max($n, $point->y);
            $e = max($e, $point->x);
            $s = min($s, $point->y);
            $w = min($w, $point->x);
        }
        
        // Instantiate ViewBounds accordingly, then return
        return new ViewBounds(new LatLng($n, $e), new LatLng($s, $w));
    }

    /**
     * @return LatLng
     */
    public function getCenter() {

        // Compute north-south and east-west differences
        $nsDiff = $this->n - $this->s;
        $ewDiff = $this->e > $this->w ?
                $this->e - $this->w : 360 - ($this->w - $this->e);

        // Compute centre's coordinates, wrap around the globe and return
        $lat = Geo::wrapLat($this->n - $nsDiff / 2);
        $lng = Geo::wrapLng($this->e - $ewDiff / 2);
        return new LatLng($lat, $lng);
    }

    /**
     * @param LatLng $center
     */
    public function setCenter(LatLng $center) {
        // Compute north-south and east-west differences
        $yN = Geo::mercatorLat($this->n);
        $yS = Geo::mercatorLat($this->s);
        $yDiff = $yN - $yS;
        
        $y = Geo::mercatorLat($center->lat);
        $this->n = Geo::mercatorLatInv($y + $yDiff / 2);
        $this->s = Geo::mercatorLatInv($y - $yDiff / 2);
        
//        $latDiff = $this->n - $this->s;
        $lngDiff = $this->e - $this->w;
        // Compute new extremities
//        $this->n = $center->lat + $latDiff / 2;
        $this->e = $center->lng + $lngDiff / 2;
//        $this->s = $center->lat - $latDiff / 2;
        $this->w = $center->lng - $lngDiff / 2;
    }
//    public function setCenter(LatLng $center) {
//        // Compute north-south and east-west differences
//        $latDiff = $this->n - $this->s;
//        $lngDiff = $this->e - $this->w;
//        // Compute new extremities
//        $this->n = $center->lat + $latDiff / 2;
//        $this->e = $center->lng + $lngDiff / 2;
//        $this->s = $center->lat - $latDiff / 2;
//        $this->w = $center->lng - $lngDiff / 2;
//    }

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
        $lngDiff = $this->e > $this->w ?
                $this->e - $this->w : 360 - ($this->w - $this->e);
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
        
        // Project this bounds' extreme latitudes onto square
        $aN = Geo::mercatorLat($this->n);
        $aS = Geo::mercatorLat($this->s);
        $aLatDiff = $aN - $aS;
        // Project given bounds' extreme latitudes onto square
        $bN = Geo::mercatorLng($bounds->n);
        $bS = Geo::mercatorLng($bounds->s);
        $bLatDiff = $bN - $bS;
        // Compute meridian zoom difference
        $latZoomDiff = floor((log($aLatDiff) - log($bLatDiff)) / log(2));
        
        $aLngDiff = $this->e - $this->w;
        $bLngDiff = $bounds->e - $bounds->w;
        // Compute parallel zoom difference
        $lngZoomDiff = floor((log($aLngDiff) - log($bLngDiff)) / log(2));
        
        // Change zoom using smaller computed zoom difference
        $zoomDiff = min($latZoomDiff, $lngZoomDiff);
        $this->changeZoom($zoomDiff);
        
        // Update zoom if given
        if ($zoom !== NULL) {
            $zoom += $zoomDiff;
        }
    }

    /**
     * @return Bounds
     */
    public function toBounds() {
        return new Bounds(new LatLng($this->n, $this->e), new LatLng($this->s, $this->w));
    }

    public function toWKT() {
        return "LINESTRING($this->e $this->n,$this->w $this->s)";
    }

    public function jsonSerialize() {
        return $this->toWKT();
    }

    public function __toString() {
        return "ViewBounds [ne=($this->n,$this->e),sw=($this->s,$this->w)]";
    }

}
