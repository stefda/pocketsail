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
     * @param object $o
     * @param string $type
     * @return \LabelModel|null
     */
    public static function fromObject($o, $type) {
        if ($o === NULL) {
            return NULL;
        }
        return new LabelModel($o, $type);
    }

    /**
     * @param int $id
     * @return \LabelModel|null
     */
    public static function load($id) {

        $r = db()->select()
                ->all('ld')
                ->col('desc', 'ldd')
                ->from('label_dynamic')->alias('ld')
                ->leftJoin('label_dynamic_descriptor')->alias('ldd')->on('sub')
                ->where('id', EQ, $id)
                ->exec();

        return LabelModel::fromObject($r->fetchObject(), 'selected');
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

        $boundsClause = self::buildBoundsClause($bounds);
        $exceptIdClause = self::buildExceptIdClause($exceptId);
        $exceptTypesClause = self::buildExceptTypesClause($exceptTypes);

        $mysql = db();

        // Initialise default query string
        $query = select($mysql)
                ->all()
                ->from('label_static')->alias('ls')
                ->where($boundsClause)
                ->und($exceptIdClause)
                ->und($exceptTypesClause)
                ->orderBy('order');

        // Change query string if raw descriptor is required
        if ($rawDesc) {
            $query = select($mysql)
                    ->all()
                    ->from('label_static')->alias('ls')
                    ->leftJoin('label_static_descriptor')->alias('lsd')->on('sub')
                    ->where('zoom', 'lsd', EQ, $zoom)
                    ->und('zoom', 'ls', EQ, $zoom)
                    ->und($boundsClause)
                    ->und($exceptIdClause)
                    ->und($exceptTypesClause)
                    ->orderBy('order');
        }

        $res = $query->exec();

        while ($o = $res->fetchObject()) {
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
            return "`id` != $id";
        }
    }

    private static function buildExceptTypesClause($types) {
        if ($types === NULL) {
            return "";
        } else {
            return "`ls`.`sub` NOT IN ('" . implode("','", $types) . "')";
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
