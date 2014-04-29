<?php

class POITypeModel implements JsonSerializable {

    public $id;
    public $parentId;
    public $name;

    public function __construct($o) {
        $this->id = $o->id;
        $this->parentId = $o->parentId;
        $this->name = $o->name;
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

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'parentId' => $this->parentId,
            'name' => $this->name
        ];
    }

}
