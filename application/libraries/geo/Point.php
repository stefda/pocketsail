<?php

class Point implements JsonSerializable {

    public $x;
    public $y;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public static function deserialize($sPoint) {
        return new Point($sPoint['x'], $sPoint['y']);
    }

    /**
     * @param binary $wkb
     * @return Point
     */
    public static function from_WKB($wkb) {
        // NULL if NULL given
        if ($wkb === NULL) {
            return NULL;
        }
        $pointInfo = unpack('Corder/Ltype/dx/dy', $wkb);
        // NULL if wkb isn't a Point
        if ($pointInfo['type'] != 1) {
            return NULL;
        }
        return new Point($pointInfo['x'], $pointInfo['y']);
    }

    /**
     * @param string $wkt
     * @return Point
     */
    public static function from_WKT($wkt) {
        $res = NULL;
        preg_match('/Point\((.*) (.*)\)/i', $wkt, $res);
        // NULL if nothing matched
        if ($res === NULL || count($res) == 0) {
            return NULL;
        }
        $x = $res[1];
        $y = $res[2];
        return new Point($x, $y);
    }

    public function to_WKT() {
        return 'Point(' . $this->x . ' ' . $this->y . ')';
    }

    public function __toString() {
        return $this->to_wkt();
    }

    public function jsonSerialize() {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }

}
