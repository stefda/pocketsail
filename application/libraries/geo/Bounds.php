<?php

class Bounds {

    public $n;
    public $e;
    public $s;
    public $w;

    public function __construct($n, $e, $s, $w) {
        $this->n = $n;
        $this->e = $e;
        $this->s = $s;
        $this->w = $w;
    }

    public function get_center() {
        $nsgf = ($this->n - $this->s) / 2;
        $ewgf = fmod($this->e - $this->w + 360, 360) / 2;
        $lat = $this->n - $nsgf;
        $lng = LatLng::wrap_lng($this->e - $ewgf);
        return new LatLng($lat, $lng);
    }

    public function set_center(LatLng $latLng) {
        $nsgf = ($this->n - $this->s) / 2;
        $ewgf = fmod($this->e - $this->w + 360, 360) / 2;
        $this->n = $latLng->lat + $nsgf;
        $this->s = $latLng->lat - $nsgf;
        $this->e = $latLng->lng + $ewgf;
        $this->w = $latLng->lng - $ewgf;
    }

    public function set_zoom($currentZoom, $targetZoom) {
        if ($currentZoom > $targetZoom) {
            while ($currentZoom > $targetZoom) {
                $this->zoom_out();
                $currentZoom--;
            }
        } elseif ($currentZoom < $targetZoom) {
            while ($currentZoom < $targetZoom) {
                $this->zoom_in();
                $currentZoom++;
            }
        }
    }

    public function fit(Bounds $bounds, $zoom) {
        if ($this->contains($bounds)) {
            while ($this->contains($bounds)) {
                $this->zoom_in();
                $zoom++;
            }
            $this->zoom_out();
            $zoom--;
        } else {
            while ($bounds->intersects($this)) {
                $this->zoom_out();
                $zoom--;
            }
        }
        return $zoom;
    }

    public function zoom_in() {
        $nsgf = ($this->n - $this->s) / 4;
        $ewgf = fmod($this->e - $this->w + 360, 360) / 4;
        $this->n -= $nsgf;
        $this->s += $nsgf;
        $this->e = LatLng::wrap_lng($this->e - $ewgf);
        $this->w = LatLng::wrap_lng($this->w + $ewgf);
    }

    public function zoom_out() {
        $nsgf = ($this->n - $this->s) / 2;
        $ewgf = fmod($this->e - $this->w + 360, 360) / 2;
        $this->n += $nsgf;
        $this->s -= $nsgf;
        $this->e = LatLng::wrap_lng($this->e + $ewgf);
        $this->w = LatLng::wrap_lng($this->w - $ewgf);
    }

    public function contains(Bounds $bounds) {
        return $this->n >= $bounds->n && $this->s <= $bounds->s && $this->e >= $bounds->e && $this->w <= $bounds->w;
    }

    public function intersects(Bounds $bounds) {
        return ($this->n >= $bounds->n && $this->s <= $bounds->s) || ($this->e >= $bounds->e && $this->w <= $bounds->w);
    }

    public static function deserialize($sBounds) {
        return new Bounds($sBounds['n'], $sBounds['e'], $sBounds['s'], $sBounds['w']);
    }

    public function serialize() {
        return [
            'n' => $this->n,
            'e' => $this->e,
            's' => $this->s,
            'w' => $this->w
        ];
    }
    
    public function to_WKT() {
        $wkt = 'POLYGON((';
        $wkt .= $this->e . ' ' . $this->n . ',';
        $wkt .= $this->e . ' ' . $this->s . ',';
        $wkt .= $this->w . ' ' . $this->s . ',';
        $wkt .= $this->w . ' ' . $this->n . ',';
        $wkt .= $this->e . ' ' . $this->n . '))';
        return $wkt;
    }

}
