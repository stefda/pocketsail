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

    private function __construct(LatLng $sw, LatLng $ne) {

        $s = $sw->lat;
        $w = $sw->lng;
        $n = $ne->lat;
        $e = $ne->lng;

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

    public function __toString() {
        return "Bounds [ne=($this->n,$this->e),sw=($this->s,$this->w)]";
    }

}
