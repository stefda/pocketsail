<?php

CL_Loader::get_instance()->library('geo/Bounds');

class LabelModel implements JsonSerializable {

    public $id;
    public $text;
    public $cat;
    public $sub;
    public $lat;
    public $lng;

    public function __construct($o) {

        $this->id = $o->id;
        $this->text = $o->text;
        $this->cat = $o->cat;
        $this->sub = $o->sub;
        $this->lat = $o->lat;
        $this->lng = $o->lng;
        $this->desc = $o->desc;
    }

    public static function loadDynamic($id) {
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT `ld`.*, `ldd`.`desc` FROM `label_dynamic` AS `ld` LEFT JOIN `label_dynamic_descriptor` AS `ldd` ON `ldd`.`sub` = `ld`.`sub` WHERE `id` = $id");
        if ($mysql->num_rows($r) == 0) {
            return null;
        }
        $o = $mysql->fetch_object($r);
        return new LabelModel($o);
    }

    public static function loadStaticByBounds($bounds, $zoom, $exceptTypes = null, $exceptId = null, $desc = 'static') {

        $labels = [];
        $mysql = CL_MySQL::get_instance();

        // Prepare query variables
        $boundsStr = self::build_query_bounds($bbox);
        $exceptTypesStr = '';
        $exceptIdStr = '';

        // Load all except given id
        if ($exceptId != null) {
            $exceptIdStr = "AND `id` != $exceptId";
        }

        // Load all except given types
        if ($exceptTypes != null && count($exceptTypes) > 0) {
            $exceptTypesStr = "AND `ls`.`sub` NOT IN ('" . implode("','", $exceptTypes) . "')";
        }

        $query = "SELECT `ls`.* FROM `label_static` AS `ls` WHERE `zoom` = $zoom AND $boundsStr $exceptIdStr $exceptTypesStr ORDER BY `order`";
        // If descriptors should be dynamic
        if ($desc === 'dynamic' || $desc === 'poi') {
            $query = "SELECT `ls`.`id`, `ls`.`text`, `ls`.`cat`, `ls`.`sub`, `ls`.`lat`, `ls`.`lng`, `lsd`.`desc` FROM `label_static` AS `ls` LEFT JOIN `label_static_descriptor` AS `lsd` ON `lsd`.`sub` = `ls`.`sub` WHERE `lsd`.`zoom` = $zoom AND `ls`.`zoom` = $zoom AND $boundsStr $exceptIdStr $exceptTypesStr ORDER BY `order`";
        }
        $r = $mysql->query($query);

        while ($o = $mysql->fetch_object($r)) {
            $labels[] = new LabelModel($o, ['dim' => $desc === 'dynamic']);
        }
        return $labels;
    }

    public static function load_static_by_types($zoom, $bbox, $types, $exceptId = null) {

        if (count($types) == 0) {
            return [];
        }

        $labels = [];
        $mysql = CL_MySQL::get_instance();
        // Prepare query variables
        $boundsStr = self::build_query_bounds($bbox);
        $typesStr = " AND `ls`.`sub` IN ('" . implode("','", $types) . "')";
        $exceptIdStr = '';
        // Load all except given id
        if ($exceptId != null) {
            $exceptIdStr = "AND `id` != $exceptId";
        }

        $query = "SELECT `ls`.`id`, `ls`.`text`, `ls`.`cat`, `ls`.`sub`, `ls`.`lat`, `ls`.`lng`, `lsd`.`desc` FROM `label_static` AS `ls` LEFT JOIN `label_static_descriptor` AS `lsd` ON `lsd`.`sub` = `ls`.`sub` WHERE `lsd`.`zoom` = $zoom AND `ls`.`zoom` = $zoom AND $boundsStr $typesStr $exceptIdStr ORDER BY `order`";
        $r = $mysql->query($query);

        while ($o = $mysql->fetch_object($r)) {
            $labels[] = new LabelModel($o, ['dim' => TRUE]);
        }
        return $labels;
    }

    public static function load_dynamic($bbox, $types, $exceptId = null) {

        $labels = [];
        $mysql = CL_MySQL::get_instance();
        // Prepare query variables
        $boundsStr = self::build_query_bounds($bbox);
        $typesStr = " AND `ld`.`sub` IN ('" . implode("','", $types) . "')";
        $exceptIdStr = '';

        // Load all except given id
        if ($exceptId != null) {
            $exceptIdStr = "AND `id` != $exceptId";
        }

        $query = "SELECT *, `ldd`.`desc` FROM `label_dynamic` AS `ld` LEFT JOIN `label_dynamic_descriptor` AS `ldd` ON `ldd`.`sub` = `ld`.`sub`  WHERE $boundsStr $typesStr $exceptIdStr ORDER BY `rank` DESC";
        $r = $mysql->query($query);

        while ($o = $mysql->fetch_object($r)) {
            $labels[] = new LabelModel($o, ['acc' => TRUE]);
        }
        return $labels;
    }

    public static function get_mcz($zoom, $bounds, $types) {

        $sql = CL_MySQL::get_instance();
        $sqlTypes = "`sub` IN ('" . implode("','", $types) . "')";

        for ($newZoom = $zoom; $newZoom >= 0; $newZoom--) {
            $sqlBounds = self::build_query_bounds($bounds);
            $r = $sql->query("SELECT * FROM `label_dynamic` WHERE $sqlBounds AND $sqlTypes");
            if ($sql->num_rows($r) > 0) {
                break;
            }
            $bounds->zoom_out();
        }
        return $newZoom;
    }

    private static function build_query_bounds($bbox) {
        $boundsStr = '';
        if ($bbox->e > $bbox->w) {
            $boundsStr = "`lat` < $bbox->n AND `lat` > $bbox->s AND `lng` < $bbox->e AND `lng` > $bbox->w";
        } else {
            $boundsStr = "`lat` < $bbox->n AND `lat` > $bbox->s AND (`lng` < $bbox->e OR `lng` > $bbox->w)";
        }
        return $boundsStr;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'name' => $this->text,
            'cat' => $this->cat,
            'sub' => $this->sub,
            'latLng' => (new LatLng($this->lat, $this->lng))->serialize(),
            'desc' => $this->desc
        ];
    }

}
