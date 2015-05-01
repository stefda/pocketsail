<?php

class PhotoModel {

    const PATH = 'data/photos/';

    private $poiId;

    public function __construct($poiId) {
        $this->poiId = $poiId;
    }

    public static function get_ids($poiId) {
        $mysql = get_mysql();
        $res = $mysql->fetch_all("SELECT id FROM photo_info WHERE poiId = ?", [$poiId]);
        $ids = [];
        foreach ($res AS $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
    
    public static function get_infos($poiId) {
        $mysql = get_mysql();
        $res = $mysql->fetch_all("SELECT id, description FROM photo_info WHERE poiId = ?", [$poiId]);
        $ids = [];
        $descriptions = [];
        foreach ($res AS $row) {
            $ids[] = $row['id'];
            $descriptions[] = $row['description'];
        }
        return [
            'ids' => $ids,
            'descriptions' => $descriptions
        ];
    }

    public static function get_description($id) {
        $mysql = get_mysql();
        $rows = $mysql->fetch_all("SELECT description FROM photo_info WHERE id = ?", [$id]);
        if (count($rows) > 0) {
            return $rows[0]['description'];
        }
        return NULL;
    }

    public static function get_main_id($poiId) {
        $mysql = get_mysql();
        $res = $mysql->fetch_all("SELECT id FROM photo_info WHERE poiId = ? AND main = ?", [$poiId, TRUE]);
        if (count($res) > 0) {
            return $res[0]['id'];
        }
        return FALSE;
    }

    public static function set_description($id, $description) {
        $mysql = get_mysql();
        $mysql->update('photo_info', [
            'description' => $description
                ], [
            'id' => $id
        ]);
    }
    
    public static function set_offset($id, $offset) {
        $mysql = get_mysql();
        $mysql->update('photo_info', [
            'offset' => $offset
                ], [
            'id' => $id
        ]);
    }

    public static function get_main_info($poiId) {
        $mysql = get_mysql();
        $res = $mysql->fetch_all("SELECT id, offset FROM photo_info WHERE poiId = ? AND main = ?", [$poiId, TRUE]);
        if (count($res) > 0) {
            return [
                'id' => $res[0]['id'],
                'offset' => $res[0]['offset']
            ];
        }
        return NULL;
    }

    public static function set_main($id) {
        $mysql = get_mysql();
        $rows = $mysql->fetch_all("SELECT poiId FROM photo_info WHERE id = $id");
        $poiId = $rows[0]['poiId'];
        $mysql->update('photo_info', ['main' => FALSE, 'offset' => NULL], ['poiId' => $poiId, 'main' => TRUE]);
        $mysql->update('photo_info', ['main' => TRUE, 'offset' => NULL], ['id' => $id]);
    }

    public function insert($original, $full, $preview, $gallery, $thumb) {

        $mysql = get_mysql();
        $mysql->insert('photo_info', [
            'poiId' => $this->poiId,
            'main' => false,
            'description' => ''
        ]);

        $id = $mysql->insert_id();
        image_write($original, BASEPATH . 'data/photos/original/' . $id . '.jpg');
        image_write($full, BASEPATH . 'data/photos/full/' . $id . '.jpg');
        image_write($preview, BASEPATH . 'data/photos/preview/' . $id . '.jpg');
        image_write($gallery, BASEPATH . 'data/photos/gallery/' . $id . '.jpg');
        image_write($thumb, BASEPATH . 'data/photos/thumb/' . $id . '.jpg');

        return $id;
    }

    public static function update($id, $original, $full, $preview, $gallery, $thumb) {
        image_write($original, BASEPATH . 'data/photos/original/' . $id . '.jpg');
        image_write($full, BASEPATH . 'data/photos/full/' . $id . '.jpg');
        image_write($preview, BASEPATH . 'data/photos/preview/' . $id . '.jpg');
        image_write($gallery, BASEPATH . 'data/photos/gallery/' . $id . '.jpg');
        image_write($thumb, BASEPATH . 'data/photos/thumb/' . $id . '.jpg');
    }

    public static function load_original($id) {
        return image_create(BASEPATH . self::PATH . 'original/' . $id . '.jpg');
    }

}
