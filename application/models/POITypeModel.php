<?php

class POITypeModel implements JsonSerializable {

    public $id;
    public $parentId;
    public $name;

    public function __construct($id, $parentId, $name) {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->name = $name;
    }

    public static function fromObject($o) {
        return new POITypeModel($o->id, $o->parentId, $o->name);
    }

    public static function loadSubs($id) {
        $subs = [];
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT * FROM `poi_type` WHERE `parentId` = '$id'");
        while ($o = $mysql->fetch_object($r)) {
            $subs[] = new POITypeModel($o);
        }
        return $subs;
    }

    public static function loadCats() {
        $cats = [];
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT * FROM `poi_type` WHERE `parentId` IS NULL");
        while ($o = $mysql->fetch_object($r)) {
            $cats[] = new POITypeModel($o);
        }
        return $cats;
    }

    public static function catFromSub($sub) {

        $r = db()->select()
                ->all('cat')
                ->from('poi_type')->alias('cat')
                ->leftJoin('poi_type')->alias('sub')->on('parentId', 'id')
                ->where('id', 'sub', EQ, $sub)
                ->exec();

        if ($r->numRows() === 0) {
            return NULL;
        }
        return self::fromObject($r->fetchObject());
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'parentId' => $this->parentId,
            'name' => $this->name
        ];
    }

}
