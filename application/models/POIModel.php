<?php

class POIModel implements JsonSerializable {

    private $o;

    public function __construct($o) {
        $this->o = $o;
    }

    public static function add($userId, $nearId, $countryId, $name, $label, $cat, $sub, LatLng $latLng, Polygon $boundary, $features) {
        
        $mysql = CL_MySQL::get_instance();
        $mysql->insert('poi_new', [
            'userId' => 1,
            'countryId' => $countryId,
            'nearId' => $nearId,
            'name' => $name,
            'label' => $label,
            'cat' => $cat,
            'sub' => $sub,
            'latLng' => $latLng,
            'boundary' => $boundary,
            'features' => $features,
            'status' => 'confirmed'
        ]);
        return $mysql->insert_id();
    }

    public static function update($id, $nearId, $countryId, $name, $label, $cat, $sub, LatLng $latLng, Polygon $boundary, $features) {

        $mysql = CL_MySQL::get_instance();
        $mysql->update('poi', "`id` = $id", [
            'nearId' => $nearId,
            'countryId' => $countryId,
            'name' => $name,
            'label' => $label,
            'cat' => $cat,
            'sub' => $sub,
            'latLng' => $latLng,
            'boundary' => $boundary,
            'features' => $features
        ]);
    }

    public static function update_admin($ID, $nearID, $countryID) {
        $mysql = CL_MySQL::get_instance();
        $mysql->query("UPDATE `poi` SET `nearID` = $nearID, `countryID` = $countryID WHERE `ID` = $ID");
    }

    public static function load($ID) {
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("
            SELECT 
                `poi`.*,
                AsText(`poi`.`latLng`) AS `latLngWKT`,
                AsText(`poi`.`boundary`) AS `boundaryWKT`,
                `poiNear`.`name` AS `nearName`,
                `poiCountry`.`name` AS `countryName`,
                `poiType`.`name` AS `subName`
            FROM `poi`
            LEFT JOIN `poi` AS `poiNear` ON `poiNear`.`id` = `poi`.`nearId`
            LEFT JOIN `poi` AS `poiCountry` ON `poiCountry`.`id` = `poi`.`countryId`
            LEFT JOIN `poi_type` AS `poiType` ON `poiType`.`id` = `poi`.`sub`
            WHERE `poi`.`ID` = $ID");
        if ($mysql->num_rows($r) == 0) {
            return NULL;
        }
        return new POIModel($mysql->fetch_object($r));
    }

    public static function load_all() {

        $pois = [];
        $mysql = CL_MySQL::get_instance();

        $r = $mysql->query("
            SELECT 
                `poi`.*,
                AsText(`poi`.`latLng`) AS `latLngWKT`,
                AsText(`poi`.`boundary`) AS `boundaryWKT`,
                `poiNear`.`name` AS `nearName`,
                `poiCountry`.`name` AS `countryName`
            FROM `poi`
            LEFT JOIN `poi` AS `poiNear` ON `poiNear`.`ID` = `poi`.`nearID`
            LEFT JOIN `poi` AS `poiCountry` ON `poiCountry`.`ID` = `poi`.`countryID`");

        while ($o = $mysql->fetch_object($r)) {
            $pois[] = new POIModel($o);
        }
        return $pois;
    }

    public static function load_by_IDs($IDs) {

        if (count($IDs) == 0) {
            return [];
        }

        $pois = [];
        $IDsString = implode(",", $IDs);
        $mysql = CL_MySQL::get_instance();

        $r = $mysql->query("
            SELECT 
                `poi`.*,
                AsText(`poi`.`latLng`) AS `latLngWKT`,
                AsText(`poi`.`boundary`) AS `boundaryWKT`,
                `poiNear`.`name` AS `nearName`,
                `poiCountry`.`name` AS `countryName`,
                `poiType`.`name` AS `subName`
            FROM `poi`
            LEFT JOIN `poi` AS `poiNear` ON `poiNear`.`ID` = `poi`.`nearID`
            LEFT JOIN `poi` AS `poiCountry` ON `poiCountry`.`ID` = `poi`.`countryID`
            LEFT JOIN `poi_type` AS `poiType` ON `poiType`.`id` = `poi`.`sub`
            WHERE `poi`.`ID` IN ($IDsString)");

        while ($o = $mysql->fetch_object($r)) {
            $pois[] = new POIModel($o);
        }
        return $pois;
    }

    public static function load_new_by_bounds(Bounds $bounds) {
        $pois = [];
        $boundsWKT = $bounds->to_WKT();
        $query = "SELECT *, AsText(`latLng`) AS `latLngWKT` FROM `poi_new` WHERE MBRContains(GeomFromText('$boundsWKT'),`latLng`)";
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query($query);
        while ($o = $mysql->fetch_object($r)) {
            $pois[] = [
                'id' => $o->id,
                'name' => $o->name,
                'latLng' => LatLNg::from_WKT($o->latLngWKT)
            ];
        }
        return $pois;
    }

    public static function load_cat_bbox(Polygon $bbox, $cat) {

        $IDs = [];
        $bboxWKT = $bbox->to_WKT();
        $catString = "'" . implode("','", $cat) . "'";
        $mysql = CL_MySQL::get_instance();

        $r = $mysql->query("
            SELECT
                `ID`
            FROM `poi`
            WHERE
                ST_Within(`position`, GeomFromText('$bboxWKT'))
                AND `cat` IN ($catString)");

        while ($o = $mysql->fetch_object($r)) {
            $IDs[] = $o->ID;
        }
        return self::load_by_IDs($IDs);
    }

    public static function find_countries(LatLng $latLng) {
        $IDs = [];
        $latLngWKT = $latLng->to_WKT();
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT `id` FROM `poi` WHERE `sub` = 'country' AND ST_Within(GeomFromText('$latLngWKT'), `boundary`)");
        while ($o = $mysql->fetch_object($r)) {
            $IDs[] = $o->id;
        }
        return self::load_by_IDs($IDs);
    }

    public static function find_nearby(LatLng $latLng) {
        $pois = [];
//        $bounds = Geo::latlng_to_bounds($latLng, 100);
//        $boundsWKT = $bounds->to_WKT();
        $latLngWKT = $latLng->to_WKT();
        $mysql = CL_MySQL::get_instance();
//        $r = $mysql->query("SELECT * FROM `poi` WHERE `sub` IN ('island', 'archipelago', 'region') AND ST_Within(`latLng`, GeomFromText('$boundsWKT')) OR ST_Within(GeomFromText('$latLngWKT'), `boundary`) ORDER BY `name`");
        $r = $mysql->query("SELECT * FROM `poi` WHERE `sub` IN ('island', 'archipelago', 'region', 'town', 'harbour', 'cove', 'bay') AND ST_Within(GeomFromText('$latLngWKT'), `boundary`) ORDER BY `name`");
        while ($o = $mysql->fetch_object($r)) {
            $pois[] = new POIModel($o);
        }
        return $pois;
    }

    public static function hit($poiID, $userID) {
        $mysql = CL_MySQL::get_instance();
        $mysql->query("INSERT IGNORE INTO `poi_hit` (`poiID`, `userID`, `date`) VALUES ($poiID, $userID, CURRENT_date())");
    }

    public function get_id() {
        return $this->o->id;
    }

    public function get_near_id() {
        return $this->o->nearId;
    }

    public function get_country_id() {
        return $this->o->countryId;
    }

    public function get_name() {
        return $this->o->name;
    }

    public function get_label() {
        return $this->o->label;
    }

    public function get_near_name() {
        return $this->o->nearName;
    }

    public function get_country_name() {
        return $this->o->countryName;
    }

    public function get_cat() {
        return $this->o->cat;
    }

    public function get_sub() {
        return $this->o->sub;
    }

    public function get_latlng() {
        return LatLng::from_WKT($this->o->latLngWKT);
    }

    public function get_boundary() {
        if ($this->o->boundaryWKT !== NULL) {
            return Polygon::from_WKT($this->o->boundaryWKT);
        }
        return NULL;
    }

    public function get_features() {
        return json_decode($this->o->features);
    }

    public function get_info() {
        $info = new stdClass();
        $info->id = $this->o->id;
        $info->nearId = $this->o->nearId;
        $info->countryId = $this->o->countryId;
        $info->name = $this->o->name;
        $info->label = $this->o->label;
        $info->nearName = $this->o->nearName;
        $info->countryName = $this->o->countryName;
        $info->cat = $this->o->cat;
        $info->sub = $this->o->sub;
        $info->subName = $this->o->subName;
        $info->latLng = $this->get_latlng();
        $info->boundary = $this->get_boundary();
        $info->features = $this->get_features();
        $info->timestamp = $this->o->timestamp;
        return $info;
    }

    public function jsonSerialize() {
        return $this->get_info();
    }

}
