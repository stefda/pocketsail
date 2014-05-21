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

    public function setCenter(LatLng $center) {
        // Compute north-south and east-west differences
        $nsDiff = $this->n - $this->s;
        $ewDiff = $this->e > $this->w ?
                $this->e - $this->w : 360 - ($this->w - $this->e);
        // Compute new extremities
        $this->n = $center->lat + $nsDiff / 2;
        $this->e = $center->lng + $ewDiff / 2;
        $this->s = Geo::wrapLng($center->lat - $nsDiff / 2);
        $this->w = Geo::wrapLng($center->lng - $ewDiff / 2);
    }

    public function zoomIn($zoomDiff = 1) {

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

    public function zoomOut($zoomDiff = 1) {

        // Compute zooming factor
        $zoomFact = pow(2, $zoomDiff) - 1;

        // Compute latitude difference between east-west extremities
        $lngDiff = $this->e > $this->w ?
                $this->e - $this->w : 360 - ($this->w - $this->e);
        $this->e += $lngDiff / 2 * $zoomFact;
        $this->w -= $lngDiff / 2 * $zoomFact;

        // Project latitudes onto a square
        $yN = Geo::mercatorLat($this->n);
        $yS = Geo::mercatorLat($this->s);

        // Compute projected differense between projects north and south
        $yDiff = $yN - $yS;

        // Compute new projected north and south
        $yN += $yDiff / 2 * $zoomFact;
        $yS -= $yDiff / 2 * $zoomFact;

        // Project back onto sphere and assign
        $this->n = Geo::mercatorLatInv($yN);
        $this->s = Geo::mercatorLatInv($yS);
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
