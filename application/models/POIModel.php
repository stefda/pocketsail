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
     * @param string[] $attrs
     * @return int
     */
    public static function add($userId, $nearId, $countryId, $name, $label, $cat, $sub, LatLng $latLng, Polygon $border, $attrs) {

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
                ->value('attributes', json_encode($attrs))
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
     * @param string[] $attrs
     */
    public static function update($id, $nearId, $countryId, $name, $label, $cat, $sub, $latLng, $border, $attrs) {
        db()->update()
                ->table('poi')
                ->set('nearId', $nearId)
                ->set('countryId', $countryId)
                ->set('name', $name)
                ->set('label', $label)
                ->set('cat', $cat)
                ->set('sub', $sub)
                ->set('latLng', $latLng === NULL ? 'NULL' : $latLng->toWKT())->op(GEOM_FROM_TEXT)
                ->set('border', $border === NULL ? 'NULL' : $border->toWKT())->op(GEOM_FROM_TEXT)
                ->set('attributes', json_encode($attrs))
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

    public function subName() {
        return $this->o->subName;
    }

    /**
     * @return LatLng
     */
    public function latLng() {
        return LatLng::fromWKT($this->o->latLngWKT);
    }

    public function border() {
        return Polygon::fromWKT($this->o->borderWKT);
    }

    public function timestamp() {
        return $this->o->timestamp;
    }

    public function attributes() {
        return json_decode($this->o->attributes);
    }

    public function info() {
        return new POIInfo($this);
    }

    public function jsonSerialize() {
        return $this->info();
    }

}

class POIInfo implements JsonSerializable {

    private $poi;

    public function __construct(POIModel $poi) {
        $this->poi = $poi;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->poi->id(),
            'name' => $this->poi->name(),
            'label' => $this->poi->label(),
            'nearName' => $this->poi->nearName(),
            'countryName' => $this->poi->countryName(),
            'subName' => $this->poi->subName(),
            'latLng' => $this->poi->latLng(),
            'timestamp' => $this->poi->timestamp()
        ];
    }

}
