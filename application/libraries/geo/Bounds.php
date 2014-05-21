<?php

/**
 * Normal bounds constrained by the [90,180,-90,-180] bounding box. The coor-
 * dinates are normalised to fit in the constraints inside of the construcotr.
 */
class Bounds {

    public $n;
    public $e;
    public $s;
    public $w;

    public function __construct(LatLng $ne, LatLng $sw) {

        $n = $ne->lat;
        $e = $ne->lng;
        $s = $sw->lat;
        $w = $sw->lng;

        // Wrap latitudes around the globe
        $n = Geo::wrapLat($n);
        $s = Geo::wrapLat($s);

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

        // Assign Bounds attributes
        $this->n = $n;
        $this->e = $e;
        $this->s = $s;
        $this->w = $w;
    }
    
    /**
     * @return LatLng The north-east corner of the bounds
     */
    public function getNorthEast() {
        return new LatLng($this->n, $this->e);
    }
    
    /**
     * @return LatLng The south-west corner of the bounds
     */
    public function getSouthWest() {
        return new LatLng($this->s, $this->w);
    }

    public function __toString() {
        return "Bounds [ne=($this->n,$this->e),sw=($this->s,$this->w)]";
    }

}
