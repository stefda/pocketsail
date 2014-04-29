<?php

class MarkerModel extends CL_Model implements JsonSerializable {
    
    private $ID;
    private $label;
    private $cat;
    private $sub;
    private $latLng;
    private $zoom;
    private $style;
    private $size;
    private $type;
    
    function __construct($o) {
        parent::__construct();
        $this->ID = $o->ID;
        $this->label = $o->label;
        $this->cat = $o->cat;
        $this->sub = $o->sub;
        $this->latLng = LatLng::from_WKB($o->latLngWKB);
        $this->zoom = @$o->zoom;
        $this->style = @$o->style;
        $this->size = @$o->size;
        $this->type = @$o->type;
    }
    
    static function load_by_bbox(Polygon $bbox, $zoom) {
        $markers = [];
        $bboxWKT = $bbox->to_WKT();
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query(""
                . "SELECT *, AsBinary(`latLng`) AS `latLngWKB`"
                . "FROM `poi_marker_zoom`"
                . "WHERE `zoom` = $zoom AND ST_Within(`latLng`, GeomFromText('$bboxWKT'))");
        while ($o = $mysql->fetch_object($r)) {
            $markers[] = new MarkerModel($o);
        }
        return $markers;
    }
    
    static function load_by_bbox_except(Polygon $bbox, $zoom, $sub) {
        $markers = [];
        $bboxWKT = $bbox->to_WKT();
        $sub = mysql_real_escape_string($sub);
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query(""
                . "SELECT *, AsBinary(`latLng`) AS `latLngWKB`"
                . "FROM `poi_marker_zoom`"
                . "WHERE `zoom` = $zoom AND ST_Within(`latLng`, GeomFromText('$bboxWKT')) AND `sub` != '$sub'");
        while ($o = $mysql->fetch_object($r)) {
            $markers[] = new MarkerModel($o);
        }
        return $markers;
    }
    
    static function load_by_sub(Polygon $bbox, $sub) {
        $markers = [];
        $bboxWKT = $bbox->to_WKT();
        $sub = mysql_real_escape_string($sub);
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query(""
                . "SELECT *, AsBinary(`latLng`) AS `latLngWKB` "
                . "FROM `poi_marker` "
                . "WHERE `sub` = '$sub' AND ST_Within(`latLng`, GeomFromText('$bboxWKT')) "
                . "ORDER BY `rank` DESC");
        while ($o = $mysql->fetch_object($r)) {
            $markers[] = new MarkerModel($o);
        }
        return $markers;
    }
    
    function jsonSerialize() {
        return [
            'ID' => $this->ID,
            'label' => $this->label,
            'lat' => $this->latLng->lat,
            'lng' => $this->latLng->lng,
            'cat' => $this->cat,
            'sub' => $this->sub,
            'style' => $this->style,
            'size' => $this->size,
            'type' => $this->type
        ];
    }
}
