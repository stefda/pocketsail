<?php

class LabelModel implements JsonSerializable {

    public $id;
    public $url;
    public $text;
    public $cat;
    public $sub;
    public $lat;
    public $lng;
    public $type;

    public function __construct($o, $type) {

        $this->id = $o->id;
        $this->url = $o->url;
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

        $r = get_mysqli()->select()
                ->all('ld')
                ->col('desc', 'ldd')
                ->from('label_dynamic')->alias('ld')
                ->leftJoin('label_dynamic_descriptor')->alias('ldd')->on('sub')
                ->where('id', EQ, $id)
                ->exec();

        return LabelModel::fromObject($r->fetchObject(), 'selected');
    }

    /**
     * @param int $id
     * @return \LabelModel|null
     */
    public static function loadDynamicByUrl($url) {

        $r = get_mysqli()->select()
                ->all('ld')
                ->col('desc', 'ldd')
                ->from('label_dynamic')->alias('ld')
                ->leftJoin('label_dynamic_descriptor')->alias('ldd')->on('sub')
                ->where('url', EQ, $url)
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

        $r = get_mysqli()->select()
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
     * @return \LabelModel[]
     */
    public static function loadStaticByBounds(Bounds $bounds, $zoom) {

        $res = get_mysqli()->select()
                ->all()
                ->from('label_static')->alias('ls')
                ->where('zoom', EQ, $zoom)
                ->andCond(self::buildBoundsClause($bounds))
                ->orderBy('order')
                ->exec();

        $labels = [];
        while ($o = $res->fetchObject()) {
            $labels[] = new LabelModel($o, 'static');
        }
        return $labels;
    }

    /**
     * @param Bounds $bounds
     * @param int $userId
     * @return \LabelModel[]
     */
    public static function loadNew(Bounds $bounds, $userId) {

        $res = get_mysqli()->select()
                ->col('id')
                ->col('name')->alias('text')
                ->col('cat')
                ->col('sub')
                ->col('lat')
                ->col('lng')
                ->from('poi_new')
                ->where('userId', EQ, $userId)
                ->andCond(self::buildBoundsClause($bounds))
                ->exec();

        $labels = [];
        while ($o = $res->fetchObject()) {
            $o->desc = NULL;
            $labels[] = new LabelModel($o, 'user');
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

    public static function loadStaticDynamicByBounds(LatLngBounds $bounds, $zoom, $exceptIds, $exceptTypes = []) {

        $res = get_mysqli()->select()
                ->all()
                ->from('label_static')->alias('ls')
                ->leftJoin('label_static_descriptor')->alias('lsd')->on('sub')
                ->where('zoom', 'lsd', EQ, $zoom)
                ->andCond('zoom', 'ls', EQ, $zoom)
                ->andCond(self::buildBoundsClause($bounds))
                ->andCond('id', NOT_IN, $exceptIds)
                ->andCond('sub', 'ls', NOT_IN, $exceptTypes)
                ->orderBy('order')
                ->exec();

        $labels = [];
        while ($o = $res->fetchObject()) {
            $labels[] = new LabelModel($o, 'dynamic');
        }
        return $labels;
    }
    
    public static function loadStaticByBounds2(LatLngBounds $bounds, $zoom, $exceptIds = [], $exceptTypes = []) {

        $res = get_mysqli()->select()
                ->all()
                ->from('label_static')->alias('ls')
                ->where('zoom', 'ls', EQ, $zoom)
                ->andCond(self::buildBoundsClause($bounds))
                ->andCond('id', NOT_IN, $exceptIds)
                ->andCond('sub', 'ls', NOT_IN, $exceptTypes)
                ->orderBy('order')
                ->exec();

        $labels = [];
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
    //public static function loadDynamicByBounds(Bounds $bounds, $types, $exceptId = 0) {
    public static function loadDynamicByBounds(LatLngBounds $bounds, $types, $exceptIds = []) {

        $res = get_mysqli()->select()
                ->all()
                ->from('label_dynamic')->alias('ld')
                ->leftJoin('label_dynamic_descriptor')->alias('ldd')->on('sub')
                ->where(self::buildBoundsClause($bounds))
                ->andCond('sub', 'ld', IN, $types)
                //->andCond('id', NE, $exceptId)
                ->andCond('id', NOT_IN, $exceptIds)
                ->orderBy('rank')
                ->exec();

        $labels = [];
        while ($o = $res->fetchObject()) {
            $labels[] = new LabelModel($o, 'dynamic');
        }
        return $labels;
    }

    public static function typesWithinBounds(LatLngBounds $bounds, $types, $exceptId = 0) {

        $res = get_mysqli()->select()
                ->all()
                ->from('label_dynamic')
                ->where(self::buildBoundsClause($bounds))
                ->andCond('sub', IN, $types)
                ->andCond('id', NE, $exceptId)
                ->groupBy('sub')
                ->exec();

        $rows = [];
        while ($o = $res->fetchObject()) {
            $rows[] = $o;
        }
        return count($types) === count($rows);
    }

    public static function oneOfTypesWithinBounds(LatLngBounds $bounds, $types, $exceptId = 0) {

        $res = get_mysqli()->select()
                ->all()
                ->from('label_dynamic')
                ->where(self::buildBoundsClause($bounds))
                ->andCond('sub', IN, $types)
                ->andCond('id', NE, $exceptId)
                ->exec();

        return $res->numRows() > 0;
    }

    private static function buildBoundsClause(LatLngBounds $bounds) {

        $s = $bounds->get_south();
        $w = $bounds->get_west();
        $n = $bounds->get_north();
        $e = $bounds->get_east();

        if ($w > $e) {
            return "(`lat` < $n AND `lat` > $s AND (`lng` < $e OR `lng` > $w))";
        } else {
            return "(`lat` < $n AND `lat` > $s AND `lng` < $e AND `lng` > $w)";
        }
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'url' => $this->url,
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
