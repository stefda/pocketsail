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

        $res = db()->select()
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

        $res = db()->select()
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

        $res = db()->select()
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

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'parentId' => $this->parentId,
            'name' => $this->name
        ];
    }

}
