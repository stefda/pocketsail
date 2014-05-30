<?php

class POIModel implements JsonSerializable {

    private $o;

    public function __construct($o) {
        $this->o = $o;
    }

    /**
     * @param int $userId
     * @param int $nearId
     * @param int $countryId
     * @param string $name
     * @param string $label
     * @param string $cat
     * @param string $sub
     * @param LatLng $latLng
     * @param Polygon $border
     * @param string[] $features
     * @return int
     */
    public static function add($userId, $nearId, $countryId, $name, $label, $cat, $sub, LatLng $latLng, Polygon $border, $features) {

        $mysql = CL_MySQLi::get_instance();
        $query = insert($mysql)
                ->into('poi_new')
                ->value('userId', $userId)
                ->value('countryId', $countryId)
                ->value('nearId', $nearId)
                ->value('name', $name)
                ->value('label', $label)
                ->value('cat', $cat)
                ->value('sub', $sub)
                ->value('latLng', $latLng->toWKT())->op(GEOM_FROM_TEXT)
                ->value('border', $border->toWKT())->op(GEOM_FROM_TEXT)
                ->value('features', json_encode($features))
                ->value('status', 'confirmed');

        $mysql->query($query);
        return $mysql->insertId();
    }

    /**
     * @param int $id
     * @param int $nearId
     * @param int $countryId
     * @param string $name
     * @param string $label
     * @param string $cat
     * @param string $sub
     * @param LatLng $latLng
     * @param Polygon $border
     * @param string[] $features
     */
    public static function update($id, $nearId, $countryId, $name, $label, $cat, $sub, LatLng $latLng, Polygon $border, $features) {
        db()->update()
                ->table('poi')
                ->set('nearId', $nearId)
                ->set('countryId', $countryId)
                ->set('name', $name)
                ->set('label', $label)
                ->set('cat', $cat)
                ->set('sub', $sub)
                ->set('latLng', $latLng->toWKT())->op(GEOM_FROM_TEXT)
                ->set('border', $border->toWKT())->op(GEOM_FROM_TEXT)
                ->set('features', json_encode($features))
                ->where('id', EQ, $id)
                ->exec();
    }

    /**
     * @param int $id
     * @param int $nearID
     * @param int $countryID
     */
    public static function updateAdmin($id, $nearID, $countryID) {
        db()->update()
                ->table('poi')
                ->set('nearId', $nearID)
                ->set('countryId', $countryID)
                ->where('id', $id)
                ->exec();
    }

    /**
     * @param int $id
     * @return \POIModel|null
     */
    public static function load($id) {

        $r = db()->select()
                ->all('poi')
                ->col('latLng', 'poi')->op(AS_TEXT)->alias('latLngWKT')
                ->col('border', 'poi')->op(AS_TEXT)->alias('borderWKT')
                ->col('name', 'poiNear')->alias('nearName')
                ->col('name', 'poiCountry')->alias('countryName')
                ->col('name', 'poiType')->alias('subName')
                ->from('poi')
                ->leftJoin('poi')->alias('poiNear')->on('id', 'nearId')
                ->leftJoin('poi')->alias('poiCountry')->on('id', 'countryId')
                ->leftJoin('poi_type')->alias('poiType')->on('id', 'sub')
                ->where('id', 'poi', EQ, $id)
                ->exec();

        if ($r->numRows() == 0) {
            return NULL;
        }
        return new POIModel($r->fetchObject());
    }

    /**
     * @param int[] $ids
     * @return \POIModel[]
     */
    public static function loadByIds($ids) {

        // Return empty array if given no ids
        if (count($ids) == 0) {
            return [];
        }

        $r = db()->select()
                ->all('poi')
                ->col('latLng', 'poi')->op(AS_TEXT)->alias('latLngWKT')
                ->col('border', 'poi')->op(AS_TEXT)->alias('borderWKT')
                ->col('name', 'poiNear')->alias('nearName')
                ->col('name', 'poiCountry')->alias('countryName')
                ->col('name', 'poiType')->alias('subName')
                ->from('poi')
                ->leftJoin('poi')->alias('poiNear')->on('id', 'nearId')
                ->leftJoin('poi')->alias('poiCountry')->on('id', 'countryId')
                ->leftJoin('poi_type')->alias('poiType')->on('id', 'sub')
                ->where('id', 'poi', IN, $ids)
                ->exec();

        while ($o = $r->fetchObject()) {
            $pois[] = new POIModel($o);
        }
        return $pois;
    }

    /**
     * @param LatLng $latLng
     * @return POIModel[]
     */
    public static function loadCountries(LatLng $latLng) {

        $r = db()->select()
                ->col('id')
                ->from('poi')
                ->where('sub', EQ, 'country')
                ->und("ST_Within(GeomFromText('{$latLng->toWKT()}}'), `border`)")
                ->exec();

        while ($o = $r->fetchObject()) {
            $ids[] = $o->id;
        }

        return self::loadByIds($ids);
    }

    /**
     * @param LatLng $latLng
     * @return \POIModel
     */
    public static function loadNearby(LatLng $latLng) {

        $r = db()->select()
                ->all()
                ->from('poi')
                ->where('sub', IN, ['island', 'archipelago', 'region', 'town', 'harbour', 'cove', 'bay', 'marina'])
                ->und("ST_Within(GeomFromText('{$latLng->toWKT()}'), `border`)")
                ->orderBy('name')
                ->exec();

        while ($o = $r->fetchObject()) {
            $pois[] = new POIModel($o);
        }
        return $pois;
    }

    public function id() {
        return $this->o->id;
    }

    public function nearId() {
        return $this->o->nearId;
    }

    public function countryId() {
        return $this->o->countryId;
    }

    public function name() {
        return $this->o->name;
    }

    public function label() {
        return $this->o->label;
    }

    public function nearName() {
        return $this->o->nearName;
    }

    public function countryName() {
        return $this->o->countryName;
    }

    public function cat() {
        return $this->o->cat;
    }

    public function sub() {
        return $this->o->sub;
    }

    public function latlng() {
        return LatLng::fromWKT($this->o->latLngWKT);
    }

    public function border() {
        if ($this->o->borderWKT !== NULL) {
            return Polygon::fromWKT($this->o->borderWKT);
        }
        return NULL;
    }

    public function features() {
        return json_decode($this->o->features);
    }

    public function info() {
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
        $info->latLng = $this->latlng();
        $info->border = $this->border();
        $info->features = $this->features();
        $info->timestamp = $this->o->timestamp;
        return $info;
    }

    public function jsonSerialize() {
        return $this->info();
    }

}
