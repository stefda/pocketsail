<?php

class Place {

    private $ID;
    private $class;
    private $type;
    private $name;
    private $location;
    private $boundary;
    private $info;
    private $userID;
    private $timestamp;

    public function __construct($o) {
        $this->ID = (int) @$o->ID;
        $this->class = @$o->class;
        $this->type = @$o->type;
        $this->name = @$o->name;
        $this->location = Point::from_WKB(@$o->locationWKB);
        $this->boundary = Polygon::from_WKB(@$o->boundaryWKB);
        $this->info = json_decode(@$o->info);
        $this->userID = (int) @$o->userID;
        $this->timestamp = @strtotime($o->timestamp);
    }

    public static function load($ID) {
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT *, AsBinary(`location`) AS `locationWKB`, AsBinary(`boundary`) AS `boundaryWKB`
            FROM `place` WHERE `ID` = $ID");
        if ($mysql->num_rows($r) == 0) {
            return NULL;
        }
        $o = $mysql->fetch_object($r);
        return new Place($o);
    }
    
    public static function load_bbox(Polygon $bbox, $classes = [], $types = []) {
        
        // At least one class and corresponding type must be specified.
        if (count($classes) == 0 || count($types) == 0) {
            return [];
        }
        
        $bboxWKT = $bbox->to_WKT();
        $classes = "'" . join("','", $classes) . "'";
        $types = "'" . join("','", $types) . "'";
        $mysql = CL_MySQL::get_instance();
        
        $r = $mysql->query("SELECT AsBinary(`location`) AS `locationWKB`, AsBinary(`boundary`) AS `boundaryWKB`
            FROM `place` WHERE `class` IN ($classes) AND `type` IN ($types) AND MBRContains(GeomFromText('$bboxWKT'), `location`)");
        
        $places = [];
        while ($o = $mysql->fetch_object($r)) {
            $places[] = new Place($o);
        }
        return $places;
    }

    public static function add($class, $type, $name, Point $location, Polygon $boundary, $data, $userID) {

        $class = mysqli_escape_string($class);
        $type = mysqli_escape_string($type);
        $name = mysqli_escape_string($name);
        $locationWKT = $location->to_WKT();
        $boundaryWKT = $boundary->to_WKT();
        $dataJSON = mysqli_escape_string(json_encode($data));

        $mysql = CL_MySQL::get_instance();
        $mysql->query("INSERT INTO `place` (`class`, `type`, `name`, `location`, `boundary`, `data`, `userID`)
            VALUES ('$class', '$type', '$name', GeomFromText('$locationWKT'), GeomFromText('$boundaryWKT'), '$dataJSON', $userID)");
        
        return $mysql->insert_id();
    }

    public function get_ID() {
        return $this->ID;
    }

    public function get_class() {
        return $this->class;
    }

    public function get_type() {
        return $this->type;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_location() {
        return $this->location;
    }

    public function get_boundary() {
        return $this->boundary;
    }
    
    public function get_nearby() {
        return [];
    }
    
    public function get_info($keys = ['info']) {
        
        $info = (object) [
            'ID' => $this->ID,
            'class' => $this->class,
            'type' => $this->type,
            'name' => $this->name,
            'location' => (object) [
                'lat' => $this->location->lat(),
                'lng' => $this->location->lng()
            ],
            'boundary' => $this->boundary === NULL ? NULL : $this->boundary->get_points(),
            'userID' => $this->userID,
            'timestamp' => $this->timestamp
        ];
        
        // Add info if requested.
        if (in_array('info', $keys)) {
            $info->info = $this->info;
        }
        
        // Add nearby if requested.
        if (in_array('nearby', $keys)) {
            $info->nearby = $this->get_nearby();
        }
        
        return $info;
    }

}