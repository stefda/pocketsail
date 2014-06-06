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
    public static function loadDynamic($id) {

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
     * @param int[] $id
     * @return \LabelModel[]|null
     */
    public static function loadDynamicByIds($ids) {

        if (count($ids) === 0) {
            return [];
        }
        
        $r = db()->select()
                ->all('ld')
                ->col('desc', 'ldd')
                ->from('label_dynamic')->alias('ld')
                ->leftJoin('label_dynamic_descriptor')->alias('ldd')->on('sub')
                ->where('id', IN, $ids)
                ->exec();

        while ($o = $r->fetchObject()) {
            $labels[] = LabelModel::fromObject($o, 'selected');
        }
        return $labels;
    }
    
    /**
     * @param Bounds $bounds
     * @param int $zoom
     * @param int $exceptId
     * @param string[] $exceptTypes
     * @param boolean $rawDesc
     * @return \LabelModel
     */
    public static function loadStaticByBounds(Bounds $bounds, $zoom) {

        $res = db()->select()
                ->all()
                ->from('label_static')->alias('ls')
                ->where('zoom', EQ, $zoom)
                ->und(self::buildBoundsClause($bounds))
                ->orderBy('order')
                ->exec();

        $labels = [];
        while ($o = $res->fetchObject()) {
            $labels[] = new LabelModel($o, 'static');
        }
        return $labels;
    }

//    /**
//     * @param Bounds $bounds
//     * @param int $zoom
//     * @param int $exceptId
//     * @param string[] $exceptTypes
//     * @return \LabelModel
//     */
//    public static function loadStaticDynamicByBounds(Bounds $bounds, $zoom, $exceptId = 0, $exceptTypes = []) {
//
//        $res = db()->select()
//                ->all()
//                ->from('label_static')->alias('ls')
//                ->leftJoin('label_static_descriptor')->alias('lsd')->on('sub')
//                ->where('zoom', 'lsd', EQ, $zoom)
//                ->und('zoom', 'ls', EQ, $zoom)
//                ->und(self::buildBoundsClause($bounds))
//                ->und('id', NE, $exceptId)
//                ->und('sub', 'ls', NOT_IN, $exceptTypes)
//                ->orderBy('order')
//                ->exec();
//
//        $labels = [];
//        while ($o = $res->fetchObject()) {
//            $labels[] = new LabelModel($o, 'dynamic');
//        }
//        return $labels;
//    }
    
        /**
     * @param Bounds $bounds
     * @param int $zoom
     * @param int $exceptId
     * @param string[] $exceptTypes
     * @return \LabelModel
     */
    public static function loadStaticDynamicByBounds(Bounds $bounds, $zoom, $exceptIds, $exceptTypes = []) {

        $res = db()->select()
                ->all()
                ->from('label_static')->alias('ls')
                ->leftJoin('label_static_descriptor')->alias('lsd')->on('sub')
                ->where('zoom', 'lsd', EQ, $zoom)
                ->und('zoom', 'ls', EQ, $zoom)
                ->und(self::buildBoundsClause($bounds))
                ->und('id', NOT_IN, $exceptIds)
                ->und('sub', 'ls', NOT_IN, $exceptTypes)
                ->orderBy('order')
                ->exec();

        $labels = [];
        while ($o = $res->fetchObject()) {
            $labels[] = new LabelModel($o, 'dynamic');
        }
        return $labels;
    }

    /**
     * @param Bounds $bounds
     * @param string[] $types
     * @param int $exceptId
     * @return \LabelModel
     */
    public static function loadDynamicByBounds(Bounds $bounds, $types, $exceptId = 0) {

        $res = db()->select()
                ->all()
                ->from('label_dynamic')->alias('ld')
                ->leftJoin('label_dynamic_descriptor')->alias('ldd')->on('sub')
                ->where(self::buildBoundsClause($bounds))
                ->und('sub', 'ld', IN, $types)
                ->und('id', NE, $exceptId)
                ->orderBy('rank')
                ->exec();

        $labels = [];
        while ($o = $res->fetchObject()) {
            $labels[] = new LabelModel($o, 'dynamic');
        }
        return $labels;
    }

    public static function typesWithinBounds(Bounds $bounds, $types, $exceptId = 0) {

        $res = db()->select()
                ->all()
                ->from('label_dynamic')
                ->where(self::buildBoundsClause($bounds))
                ->und('sub', IN, $types)
                ->und('id', NE, $exceptId)
                ->groupBy('sub')
                ->exec();

        $rows = [];
        while ($o = $res->fetchObject()) {
            $rows[] = $o;
        }
        return count($types) === count($rows);
    }

    //Helper method
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

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'cat' => $this->cat,
            'sub' => $this->sub,
            'latLng' => new LatLng($this->lat, $this->lng),
            'desc' => $this->desc,
            'type' => $this->type
        ];
    }

    public function __toString() {
        return json_encode($this->jsonSerialize());
    }

}
