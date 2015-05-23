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

        $res = get_mysqli()->select()
                ->all()
                ->from('poi_type')
                ->where('parentId', EQ, $id)
                ->exec();

        $subs = [];
        while ($o = $res->fetchObject()) {
            $subs[] = self::fromObject($o);
        }
        return $subs;
    }

    public static function loadCats() {

        $res = get_mysqli()->select()
                ->all()
                ->from('poi_type')
                ->where('parentId', IS, NULL)
                ->exec();

        $cats = [];
        while ($o = $res->fetchObject()) {
            $cats[] = self::fromObject($o);
        }
        return $cats;
    }

    public static function catFromSub($sub) {

        $res = get_mysqli()->select()
                ->all('cat')
                ->from('poi_type')->alias('cat')
                ->leftJoin('poi_type')->alias('sub')->on('parentId', 'id')
                ->where('id', 'sub', EQ, $sub)
                ->exec();

        if ($res->numRows() === 0) {
            return NULL;
        }
        return self::fromObject($res->fetchObject());
    }
    
    public static function cats_name_map() {
        
        $map = [];
        $mysql = get_mysql();
        $cats = $mysql->fetch_all("SELECT * FROM `poi_type` WHERE `parentId` IS NULL");
        
        foreach ($cats AS $cat) {
            $map[$cat['id']] = $cat['name'];
        }
        
        return $map;
    }
    
    public static function subs_name_map() {
        
        $map = [];
        $mysql = get_mysql();
        $subs = $mysql->fetch_all("SELECT * FROM `poi_type` WHERE `parentId` IS NOT NULL");
        
        foreach ($subs AS $sub) {
            $map[$sub['id']] = $sub['name'];
        }
        
        return $map;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'parentId' => $this->parentId,
            'name' => $this->name
        ];
    }

}
