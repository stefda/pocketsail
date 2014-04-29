<?php

class Area {

    private $ID;
    private $name;
    private $class;
    private $type;
    private $position;
    private $bounds;
    private $info;
    private $inc;
    private $exc;
    private $userID;
    private $time;

    private function __construct($o) {
        $this->ID = $o->ID;
        $this->class = $o->class;
        $this->type = $o->type;
        $this->name = $o->name;
        $this->position = Point::from_WKB($o->positionWKB);
        $this->bounds = Polygon::from_WKB($o->boundsWKB);
        $this->info = json_decode($o->info);
        $this->inc = json_decode($o->inc);
        $this->exc = json_decode($o->exc);
        $this->userID = $o->userID;
        $this->time = $o->time;
    }

    public static function load($ID) {
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT *, AsBinary(`position`) AS positionWKB, AsBinary(`bounds`) AS boundsWKB FROM `area` WHERE `ID` = $ID");
        if ($mysql->num_rows($r) == 0) {
            return NULL;
        }
        $o = $mysql->fetch_object($r);
        return new Area($o);
    }

    public static function add($type, $subtype, Point $latlng, Polygon $bounds, $name, $info) {
        
        $type = mysqli_escape_string($type);
        $subtype = mysqli_escape_string($subtype);
        $latlngWKT = $latlng->to_wkt();
        $boundsWKT = $bounds->to_wkt();
        $name = mysqli_escape_string($name);
        $infoJSON = mysqli_escape_string(json_encode($info));

        $mysql = CL_MySQL::get_instance();
        $mysql->query("INSERT INTO `area` (ID, type, subtype, latlng, bounds, name, info)
            VALUES (NULL, '$type', '$subtype', GeomFromText('$latlngWKT'), GeomFromText('$boundsWKT'), '$name', '$infoJSON')");
    }
    
    public function get_name() {
        return $this->name;
    }

    public function get_lat() {
        return $this->position->lat();
    }

    public function get_lng() {
        return $this->position->lng();
    }

    /**
     * @return Polygon
     */
    public function get_position() {
        return $this->position;
    }
    
    /**
     * @return Polygon
     */
    public function get_bounds() {
        return $this->bounds;
    }
    
    public function get_info() {
        return $this->info;
    }
    
    public function get_inc() {
        return $this->inc;
    }
    
    public function get_exc() {
        return $this->exc;
    }

    public function __toString() {
        $str = $this->name . ' (' . $this->ID . '), ' . $this->latlng;
        return $str;
    }

}