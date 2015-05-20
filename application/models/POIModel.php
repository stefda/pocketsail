<?php

class POIModel implements JsonSerializable {

    private $id;
    private $url;
    private $nearId;
    private $countryId;
    private $nearName;
    private $countryName;
    private $userId;
    private $name;
    private $label;
    private $cat;
    private $sub;
    private $subName;
    private $lat;
    private $lng;
    private $border;
    private $attrs;
    private $rank;
    private $timestamp;

    public function __construct($o) {
        
        $this->id = $o->id;
        $this->url = $o->url;
        $this->nearId = $o->nearId;
        $this->nearName = $o->nearName;
        $this->countryId = $o->countryId;
        $this->countryName = $o->countryName;
        $this->userId = $o->userId;
        $this->name = $o->name;
        $this->label = $o->label;
        $this->cat = $o->cat;
        $this->sub = $o->sub;
        $this->subName = $o->subName;
        $this->lat = $o->lat;
        $this->lng = $o->lng;
        $this->border = Polygon::from_wkt($o->borderWKT);
        $this->attrs = json_decode($o->attrs);
        $this->rank = $o->rank;
        $this->timestamp = $o->timestamp;
    }

    public static function fromObject($o) {
        return new POIModel($o);
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
    public static function add($userId, $url, $nearId, $countryId, $name, $label, $cat, $sub, $latLng, $border, $attrs) {

        $mysql = CL_MySQLi::get_instance();
        $query = insert($mysql)
                ->into('poi')
                ->value('url', $url)
                ->value('userId', $userId)
                ->value('countryId', $countryId)
                ->value('nearId', $nearId)
                ->value('name', $name)
                ->value('label', $label)
                ->value('cat', $cat)
                ->value('sub', $sub)
                ->value('lat', $latLng->lat())
                ->value('lng', $latLng->lng())
                ->value('border', $border === NULL ? 'NULL' : $border->to_wkt())->op(GEOM_FROM_TEXT)
                ->value('attrs', json_encode($attrs));

        $mysql->query($query);
        return $mysql->insertId();
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
    public static function insert($id, $url, $userId, $nearId, $countryId, $name, $label, $cat, $sub, $latLng, $border, $attrs) {

        $mysql = CL_MySQLi::get_instance();
        $query = insert($mysql)
                ->into('poi')
                ->value('id', $id)
                ->value('url', $url)
                ->value('userId', $userId)
                ->value('countryId', $countryId)
                ->value('nearId', $nearId)
                ->value('name', $name)
                ->value('label', $label)
                ->value('cat', $cat)
                ->value('sub', $sub)
                ->value('lat', $latLng->lat())
                ->value('lng', $latLng->lng())
                ->value('border', $border === NULL ? 'NULL' : $border->to_wkt())->op(GEOM_FROM_TEXT)
                ->value('attrs', json_encode($attrs));

        $mysql->query($query);
        return $mysql->insertId();
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
    public static function addNew($url, $nearId, $countryId, $userId, $name, $label, $cat, $sub, $latLng, $border, $attrs) {
        
        $mysql = CL_MySQLi::get_instance();
        $query = insert($mysql)
                ->into('poi_new')
                ->value('url', $url)
                ->value('userId', $userId)
                ->value('countryId', $countryId)
                ->value('nearId', $nearId)
                ->value('name', $name)
                ->value('label', $label)
                ->value('cat', $cat)
                ->value('sub', $sub)
                ->value('lat', $latLng->lat())
                ->value('lng', $latLng->lng())
                ->value('border', $border === NULL ? 'NULL' : $border->to_wkt())->op(GEOM_FROM_TEXT)
                ->value('attrs', json_encode($attrs));
        
        $mysql->query($query);
        return $mysql->insertId();
    }

    /**
     * @param int $id
     * @param int $nearId
     * @param int $countryId
     * @param int $userId
     * @param string $name
     * @param string $label
     * @param string $cat
     * @param string $sub
     * @param LatLng $latLng
     * @param Polygon $border
     * @param string[] $attrs
     * @return int
     */
    public static function addEdit($id, $nearId, $countryId, $userId, $name, $label, $cat, $sub, $latLng, $border, $attrs) {

        get_mysqli()->insert()
                ->into('poi_edit')
                ->value('id', $id)
                ->value('countryId', $countryId)
                ->value('nearId', $nearId)
                ->value('userId', $userId)
                ->value('name', $name)
                ->value('label', $label)
                ->value('cat', $cat)
                ->value('sub', $sub)
                ->value('lat', $latLng->lat())
                ->value('lng', $latLng->lng())
                ->value('border', $border === NULL ? 'NULL' : $border->to_wkt())->op(GEOM_FROM_TEXT)
                ->value('attrs', json_encode($attrs))
                ->exec();

        return get_mysqli()->insertId();
    }

    /**
     * @param int $id
     * @param int $nearId
     * @param int $countryId
     * @param int $userId
     * @param string $name
     * @param string $label
     * @param string $cat
     * @param string $sub
     * @param LatLng $latLng
     * @param Polygon $border
     * @param string[] $attrs
     * @param float $rank
     * @param string $timestamp
     * @return int
     */
    public static function addArchive($id, $nearId, $countryId, $userId, $name, $label, $cat, $sub, $latLng, $border, $attrs, $rank, $timestamp) {

        get_mysqli()->insert()
                ->into('poi_archive')
                ->value('id', $id)
                ->value('countryId', $countryId)
                ->value('nearId', $nearId)
                ->value('userId', $userId)
                ->value('name', $name)
                ->value('label', $label)
                ->value('cat', $cat)
                ->value('sub', $sub)
                ->value('lat', $latLng->lat())
                ->value('lng', $latLng->lng())
                ->value('border', $border === NULL ? 'NULL' : $border->to_wkt())->op(GEOM_FROM_TEXT)
                ->value('attrs', json_encode($attrs))
                ->value('rank', $rank)
                ->value('timestamp', $timestamp)
                ->exec();

        return get_mysqli()->insertId();
    }

    /**
     * @param int $id
     * @param int $nearId
     * @param int $countryId
     * @param int $userId
     * @param string $name
     * @param string $label
     * @param string $cat
     * @param string $sub
     * @param LatLng $latLng
     * @param Polygon $border
     * @param string[] $attrs
     */
    public static function update($id, $url, $nearId, $countryId, $userId, $name, $label, $cat, $sub, $latLng, $border, $attrs) {

        get_mysqli()->update()
                ->table('poi')
                ->set('url', $url)
                ->set('nearId', $nearId)
                ->set('countryId', $countryId)
                ->set('userId', $userId)
                ->set('name', $name)
                ->set('label', $label)
                ->set('cat', $cat)
                ->set('sub', $sub)
                ->set('lat', $latLng->lat())
                ->set('lng', $latLng->lng())
                ->set('border', $border === NULL ? 'NULL' : $border->to_wkt())->op(GEOM_FROM_TEXT)
                ->set('attrs', json_encode($attrs))
                ->set('timestamp')->op('CURRENT_TIMESTAMP')
                ->where('id', EQ, $id)
                ->exec();
    }

    public static function editExists($id, $userId) {

        $res = get_mysqli()->select()
                ->col('id')
                ->from('poi_edit')
                ->where('id', EQ, $id)
                ->andCond('userId', EQ, $userId)
                ->exec();

        return $res->numRows() != 0;
    }

    /**
     * @param int $id
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
     */
    public static function updateEdit($id, $userId, $nearId, $countryId, $name, $label, $cat, $sub, $latLng, $border, $attrs) {

        get_mysqli()->update()
                ->table('poi_edit')
                ->set('nearId', $nearId)
                ->set('countryId', $countryId)
                ->set('userId', $userId)
                ->set('name', $name)
                ->set('label', $label)
                ->set('cat', $cat)
                ->set('sub', $sub)
                ->set('lat', $latLng->lat())
                ->set('lng', $latLng->lng())
                ->set('border', $border === NULL ? 'NULL' : $border->to_wkt())->op(GEOM_FROM_TEXT)
                ->set('attrs', json_encode($attrs))
                ->set('timestamp')->op('CURRENT_TIMESTAMP')
                ->where('id', EQ, $id)
                ->andCond('userId', EQ, $userId)
                ->exec();
    }

    /**
     * @param int $id
     * @return \POIModel|null
     */
    public static function load($id) {

        $r = get_mysqli()->select()
                ->all('poi')
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

        return POIModel::fromObject($r->fetchObject());
    }
    
    /**
     * @param int $id
     * @return \POIModel|null
     */
    public static function loadByUrl($url) {
        
        $r = get_mysqli()->select()
                ->all('poi')
                ->col('border', 'poi')->op(AS_TEXT)->alias('borderWKT')
                ->col('name', 'poiNear')->alias('nearName')
                ->col('name', 'poiCountry')->alias('countryName')
                ->col('name', 'poiType')->alias('subName')
                ->from('poi')
                ->leftJoin('poi')->alias('poiNear')->on('id', 'nearId')
                ->leftJoin('poi')->alias('poiCountry')->on('id', 'countryId')
                ->leftJoin('poi_type')->alias('poiType')->on('id', 'sub')
                ->where('url', 'poi', EQ, $url)
                ->exec();

        if ($r->numRows() == 0) {
            return NULL;
        }

        return POIModel::fromObject($r->fetchObject());
    }
    
    /**
     * @param int $id
     * @return \POIModel|null
     */
    public static function loadAll() {

        $r = get_mysqli()->select()
                ->all('poi')
                ->col('border', 'poi')->op(AS_TEXT)->alias('borderWKT')
                ->col('name', 'poiNear')->alias('nearName')
                ->col('name', 'poiCountry')->alias('countryName')
                ->col('name', 'poiType')->alias('subName')
                ->from('poi')
                ->leftJoin('poi')->alias('poiNear')->on('id', 'nearId')
                ->leftJoin('poi')->alias('poiCountry')->on('id', 'countryId')
                ->leftJoin('poi_type')->alias('poiType')->on('id', 'sub')
                ->exec();
        
        $pois = [];
        
        while ($o = $r->fetchObject()) {
            $pois[] = POIModel::fromObject($o);
        }
        return $pois;
    }

    /**
     * @param int $id
     * @return \POIModel|null
     */
    public static function loadNew($id) {

        $r = get_mysqli()->select()
                ->all('poi_new')
                ->col('border', 'poi_new')->op(AS_TEXT)->alias('borderWKT')
                ->col('name', 'poiNear')->alias('nearName')
                ->col('name', 'poiCountry')->alias('countryName')
                ->col('name', 'poiType')->alias('subName')
                ->from('poi_new')
                ->leftJoin('poi')->alias('poiNear')->on('id', 'nearId')
                ->leftJoin('poi')->alias('poiCountry')->on('id', 'countryId')
                ->leftJoin('poi_type')->alias('poiType')->on('id', 'sub')
                ->where('id', 'poi_new', EQ, $id)
                ->exec();

        if ($r->numRows() == 0) {
            return NULL;
        }
        return POIModel::fromObject($r->fetchObject());
    }

    /**
     * @param int[] $ids
     * @return \POIModel[]
     */
    public static function loadByIds($ids) {

        // Return empty array if no ids given
        if (count($ids) == 0) {
            return [];
        }

        $r = get_mysqli()->select()
                ->all('poi')
                ->col('border', 'poi')->op(AS_TEXT)->alias('borderWKT')
                ->col('name', 'poiNear')->alias('nearName')
                ->col('name', 'poiCountry')->alias('countryName')
                ->col('name', 'poiType')->alias('subName')
                ->from('poi')
                ->leftJoin('poi')->alias('poiNear')->on('id', 'nearId')
                ->leftJoin('poi')->alias('poiCountry')->on('id', 'countryId')
                ->leftJoin('poi_type')->alias('poiType')->on('id', 'sub')
                ->where('id', 'poi', IN, $ids)
                ->orderBy('name', 'poi')
                ->exec();

        while ($o = $r->fetchObject()) {
            $pois[] = new POIModel($o);
        }
        return $pois;
    }

    public static function loadWithinBorder(Polygon $border, $types, $exceptId = 0) {

        $res = get_mysqli()->select()
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
                ->where("ST_Within(GeomFromText(POINT(`poi`.`lng`, `poi`.`lat`)), GeomFromText({$border->to_wkt()}))")
                ->andCond('sub', 'poi', IN, $types)
                ->andCond('id', 'poi', NE, $exceptId)
                ->exec();

        while ($o = $res->fetchObject()) {
            $pois[] = new POIModel($o);
        }
        return $pois;
    }

    /**
     * @param LatLng $latLng
     * @return \POIModel[]
     */
    public static function loadCountries(LatLng $latLng) {

        $r = get_mysqli()->select()
                ->col('id')
                ->from('poi')
                ->where('sub', EQ, 'country')
                ->andCond("ST_Within(GeomFromText('{$latLng->to_wkt()}'), `border`)")
                ->exec();

        $ids = [];
        while ($o = $r->fetchObject()) {
            $ids[] = $o->id;
        }

        return self::loadByIds($ids);
    }
    
    public static function load_pois_near($poiId) {
        
        $mysql = get_mysql();
        $rows = $mysql->fetch_all("SELECT id, name, cat, sub FROM poi WHERE nearId = ?", [$poiId]);
    }

    /**
     * @param LatLng $latLng
     * @return \POIModel
     */
    public static function loadNears(LatLng $latLng) {

        $r = get_mysqli()->select()
                ->col('id')
                ->from('poi')
                ->where("ST_Within(GeomFromText('{$latLng->to_wkt()}'), `border`)")
                ->exec();

        $ids = [];
        while ($o = $r->fetchObject()) {
            $ids[] = $o->id;
        }
        return self::loadByIds($ids);
    }

    public function id() {
        return $this->id;
    }
    
    public function url() {
        return $this->url;
    }

    public function nearId() {
        return $this->nearId;
    }

    public function nearName() {
        return $this->nearName;
    }

    public function countryId() {
        return $this->countryId;
    }

    public function countryName() {
        return $this->countryName;
    }

    public function userId() {
        return $this->userId;
    }

    public function name() {
        return $this->name;
    }

    public function label() {
        return $this->label;
    }

    public function cat() {
        return $this->cat;
    }

    public function sub() {
        return $this->sub;
    }

    public function subName() {
        return $this->subName;
    }

    public function lat() {
        return $this->lat;
    }

    public function lng() {
        return $this->lng;
    }

    public function latLng() {
        return new LatLng($this->lat, $this->lng);
    }

    /**
     * @return Polygon
     */
    public function border() {
        return $this->border;
    }
    
    public function has_border() {
        return $this->border !== NULL;
    }

    public function attrs() {
        return $this->attrs;
    }

    public function rank() {
        return $this->rank;
    }

    public function timestamp() {
        return $this->timestamp;
    }

    public function toObject() {
        $res = new stdClass();
        $res->id = $this->id;
        $res->url = $this->url;
        $res->nearId = $this->nearId;
        $res->nearName = $this->nearName;
        $res->countryId = $this->countryId;
        $res->countryName = $this->countryName;
        $res->userId = $this->userId;
        $res->name = $this->name;
        $res->label = $this->label;
        $res->cat = $this->cat;
        $res->sub = $this->sub;
        $res->subName = $this->subName;
        $res->lat = $this->lat;
        $res->lng = $this->lng;
        $res->latLng = $this->latLng();
        $res->border = $this->border;
        $res->attrs = $this->attrs;
        $res->rank = $this->rank;
        $res->timestamp = $this->timestamp;
        return $res;
    }

    public function jsonSerialize() {
        return $this->toObject();
    }

}
