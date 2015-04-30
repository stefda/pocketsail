<?php

class PhotosModel {

    const PATH = 'data/images/';
    
    public static function has_photos($id) {
        return file_exists(BASEPATH . self::PATH . $id);
    }
    
    public static function init($id) {
        
        $path = BASEPATH . self::PATH . $id;
        
        if (file_exists($path)) {
            return FALSE;
        }
        
        mkdir($path);
        mkdir($path . '/full');
        mkdir($path . '/preview');
        mkdir($path . '/gallery');
        mkdir($path . '/thumb');
        
        return TRUE;
    }
    
    public static function get_names($id) {
        $dir = opendir(BASEPATH . self::PATH . $id . '/full');
        $names = [];
        while ($name = readdir($dir)) {
            if ($name !== '..' && $name !== '.')
            $names[] = $name;
        }
        return $names;
    }
    
    public static function get_next_id() {
        
        $imageId = 0;
        
        foreach ($names AS $name) {
            $photoId = substr($name, 0, -4);
            $imageId = max([$imageId, $photoId]);
        }

        $path = BASEPATH . self::PATH . $id;
        return $imageId++;
    }
    
    public static function insert($id, $full, $preview, $gallery, $thumb) {
        
        $names = self::get_names($id);
        $imageId = 0;
        
        foreach ($names AS $name) {
            $photoId = substr($name, 0, -4);
            $imageId = max([$imageId, $photoId]);
        }

        $path = BASEPATH . self::PATH . $id;
        $imageId++;
        
        self::insert_type($path, 'full', $imageId, $full);
        self::insert_type($path, 'preview', $imageId, $preview);
        self::insert_type($path, 'gallery', $imageId, $gallery);
        self::insert_type($path, 'thumb', $imageId, $thumb);
    }
    
    public static function get_path($id, $type) {
        return BASEPATH . self::PATH . '/' . $type . '/' . $id . '.jpg';
    }
    
    private static function insert_type($path, $type, $imageId, $data) {
        imagejpeg($data, $path . '/' . $type . '/' . $imageId . '.jpg', 100);
    }

}

//class ImageModel {
//
//    public static function add($poiId, $userId, $credits, $description) {
//
//        db()->insert()
//                ->into('image')
//                ->value('poiId', $poiId)
//                ->value('userId', $userId)
//                ->value('credits', $credits)
//                ->value('description', $description)
//                ->exec();
//
//        return db()->insertId();
//    }
//    
//    public static function id2path($id) {
//        $fldName = floor($id / 100);
//        $path = '/db/images/full/' . $fldName . '/';
//        $imageName = ($id % 100) . '.jpeg';
//        return $path . $imageName;
//    }
//
//}
