<?php

class PhotoModel {

    const PATH = 'data/images/';

    private $poiId;

    public function __construct($poiId) {
        $this->poiId = $poiId;
    }

    public function get_ids() {
        $mysql = get_mysql();
        $res = $mysql->fetch_all("SELECT id FROM photo_info WHERE poiId = ?", [$this->poiId]);
        $ids = [];
        foreach ($res AS $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    public static function get_info($id) {
        $mysql = get_mysql();
        $rows = $mysql->fetch_all("SELECT title, description FROM photo_info WHERE id = ?", [$id]);
        if (count($rows) > 0) {
            return [
                'title' => $rows[0]['title'],
                'description' => $rows[0]['description']
            ];
        }
        return NULL;
    }

    public function get_main_id() {
        $mysql = get_mysql();
        $res = $mysql->fetch_all("SELECT id FROM photo_info WHERE poiId = ? AND main = 1", [$this->poiId]);
        if (count($res) > 0) {
            return $res[0]['id'];
        }
        return FALSE;
    }

    public static function set_info($id, $info) {
        $mysql = get_mysql();
        $mysql->update('photo_info', [
            'title' => $info['title'],
            'description' => $info['description']
                ], [
            'id' => $id
        ]);
    }

    public static function set_main($id) {
        $mysql = get_mysql();
        $mysql->update('photo_info', ['main' => TRUE], ['id' => $id]);
    }

    public function insert($title, $description, $full, $preview, $gallery, $thumb) {
        $mysql = get_mysql();
        $mysql->insert('photo_info', [
            'poiId' => $this->poiId,
            'main' => false,
            'title' => $title,
            'description' => $description
        ]);
        $id = $mysql->insert_id();
        image_write($full, BASEPATH . 'data/photos/full/' . $id . '.jpg');
        image_write($preview, BASEPATH . 'data/photos/preview/' . $id . '.jpg');
        image_write($gallery, BASEPATH . 'data/photos/gallery/' . $id . '.jpg');
        image_write($thumb, BASEPATH . 'data/photos/thumb/' . $id . '.jpg');
    }

}
