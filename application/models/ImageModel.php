<?php

class ImageModel {

    public static function add($poiId, $userId, $credits, $description) {

        db()->insert()
                ->into('image')
                ->value('poiId', $poiId)
                ->value('userId', $userId)
                ->value('credits', $credits)
                ->value('description', $description)
                ->exec();

        return db()->insertId();
    }
    
    public static function id2path($id) {
        $fldName = floor($id / 100);
        $path = '/db/images/full/' . $fldName . '/';
        $imageName = ($id % 100) . '.jpeg';
        return $path . $imageName;
    }

}
