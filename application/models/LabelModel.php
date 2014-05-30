<?php

class LabelModel implements JsonSerializable {

    public $id;
    public $text;
    public $cat;
    public $sub;
    public $lat;
    public $lng;
    public $type;

    public function __construct($o, $type) {

        $this->id = $o->id;
        $this->text = $o->text;
        $this->cat = $o->cat;
        $this->sub = $o->sub;
        $this->lat = $o->lat;
        $this->lng = $o->lng;
        $this->desc = $o->desc;
        $this->type = $type;
    }

    /**
     * @param int $id
     * @return \LabelModel|null
     */
    public static function load($id) {
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT * FROM `label_dynamic` WHERE `id` = $id");
        if ($mysql->num_rows($r) === 0) {
            return NULL;
        }
        $o = $mysql->fetch_object($r);
        return new LabelModel($o, 'selected');
    }

    /**
     * @param Bounds $bounds
     * @param int $zoom
     * @param int $exceptId
     * @param string[] $exceptTypes
     * @param boolean $rawDesc
     * @return \LabelModel
     */
    public static function loadStaticByBounds(Bounds $bounds, $zoom, $exceptId = NULL, $exceptTypes = NULL, $rawDesc = FALSE) {

        $labels = [];
        $boundsClause = self::buildBoundsClause($bounds);
        $exceptIdClause = self::buildExceptIdClause($exceptId);
        $exceptTypesClause = self::buildExceptTypesClause($exceptTypes);

        // Initialise default query string
        $q = "SELECT * FROM `label_static` AS `ls` WHERE $boundsClause $exceptIdClause $exceptTypesClause ORDER BY `order`";

        // Change query string if raw descriptor is required
        if ($rawDesc) {
            $q = "SELECT * FROM `label_static` AS `ls` "
                    . "LEFT JOIN `label_static_descriptor` AS `lsd` ON `lsd`.`sub` = `ls`.`sub` "
                    . "WHERE `lsd`.`zoom` = $zoom AND `ls`.`zoom` = $zoom AND $boundsClause $exceptIdClause $exceptTypesClause "
                    . "ORDER BY `order`";
        }

        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query($q);

        while ($o = $mysql->fetch_object($r)) {
            $labels[] = new LabelModel($o, 'static');
        }

        return $labels;
    }

    /**
     * @param Bounds $bounds
     * @param string[] $types
     * @param int $exceptId
     * @return \LabelModel
     */
    public static function loadDynamicByBounds(Bounds $bounds, $types, $exceptId = NULL) {

        $labels = [];
        $boundsClause = self::buildBoundsClause($bounds);
        $typesClause = "AND `ld`.`sub` IN ('" . implode("','", $types) . "')";
        $exceptIdClause = self::buildExceptIdClause($exceptId);

        $q = "SELECT * FROM `label_dynamic` AS `ld` "
                . "LEFT JOIN `label_dynamic_descriptor` AS `ldd` ON `ldd`.`sub` = `ld`.`sub` "
                . "WHERE $boundsClause $typesClause $exceptIdClause "
                . "ORDER BY `rank`";

        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query($q);

        while ($o = $mysql->fetch_object($r)) {
            $labels[] = new LabelModel($o, 'dynamic');
        }

        return $labels;
    }

    private static function buildBoundsClause(Bounds $bounds) {

        $s = $bounds->s();
        $w = $bounds->w();
        $n = $bounds->n();
        $e = $bounds->e();

        if ($w > $e) {
            return "(`lat` < $n AND `lat` > $s AND (`lng` < $e OR `lng` > $w))";
        } else {
            return "(`lat` < $n AND `lat` > $s AND `lng` < $e AND `lng` > $w)";
        }
    }

    private static function buildExceptIdClause($id) {
        if ($id === NULL) {
            return "";
        } else {
            return "AND `id` != $id";
        }
    }

    private static function buildExceptTypesClause($types) {
        if ($types === NULL) {
            return "";
        } else {
            return "AND `ls`.`sub` NOT IN ('" . implode("','", $types) . "')";
        }
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'cat' => $this->cat,
            'sub' => $this->sub,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'type' => $this->type
        ];
    }

}
