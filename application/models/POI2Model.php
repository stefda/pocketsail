<?php

/**
 * Description of POI2Model
 *
 * @author David Stefan
 */
class POI2Model {

    private $id;
    private $url;
    private $name;
    private $attrs;
    private $timestamp;

    public function __construct() {
        
    }

    public static function fromObject($o) {
        $poi = new POI2Model();
        $poi->setId($o->id);
        $poi->setUrl($o->url);
        $poi->setName($o->name);
        $poi->setAttrs($o->attrs);
        return $poi;
    }
    
    public static function fromDoc($doc) {
        
    }

    public static function load($id) {

        $db = cl_mysql();
        $db->select('poi', '*', [
            'id' => $id
        ]);

        if (($o = $db->fetchObject()) !== NULL) {
            return POI2Model::fromObject($o);
        }
    }

    public function update() {
        $this->_update('poi');
    }

    public function updateTemp() {
        $this->_update('poi_temp');
    }

    private function _update($table) {
        $db = cl_mysql();
        $db->update($table, [
            'url' => $this->url,
            'name' => $this->name,
            'attrs' => $this->attrs,
            'timestamp' => ['NOW' => []]
                ], [
            'id' => $this->id
        ]);
    }

    /**
     * @throws ActiveRecordException
     */
    public function saveToTemp() {
        $db = cl_mysql();
        $db->insert('poi_temp', [
            'id' => $this->id,
            'url' => $this->url,
            'name' => $this->name,
            'attrs' => json_encode($this->attrs)
        ]);
    }

    public function saveToMongo() {

        $db = CL_Mongo::getInstance();
        $res = $db->insert('poi', $this->toDoc());

        if (!$res) {
            throw new ActiveRecordException($db->getError());
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setAttrs($attrs) {
        $this->attrs = $attrs;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function toDoc() {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'name' => $this->name,
            'attrs' => $this->attrs
        ];
    }

}
