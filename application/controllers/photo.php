<?php

class Photo extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('image');
        $this->load->model('PhotoModel');
    }

    function index() {
        // DO NOTHING
    }

    function init_load_frame() {
        return '';
    }

    function upload() {

        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $files = [];

        foreach ($_FILES['photo']['tmp_name'] AS $file) {
            $files[] = $file;
        }

        foreach ($files AS $file) {
            $img = image_create($file);
            $full = image_resize($img, 1600, 900);
            $gallery = image_resize_width($img, 580);
            $preview = image_resize_crop($img, 216, 216);
            $thumb = image_resize_crop($img, 100, 100);
            $photo = new PhotoModel($poiId);
            $photo->insert($img, $full, $preview, $gallery, $thumb);
        }

        $ids = PhotoModel::get_ids($poiId);
        $mainId = PhotoModel::get_main_id($poiId);

        // First photos for this POI, set main!
        if (count($files) === count($ids)) {
            PhotoModel::set_main($ids[0]);
            $mainId = $ids[0];
        }

        return [
            'status' => 'OK',
            'ids' => $ids,
            'main' => $mainId
        ];
    }

    /**
     * @AjaxCallable
     */
    function delete() {

        $args = deserialize_input(INPUT_POST, [
            'id' => FILTER_VALIDATE_INT,
            'poiId' => FILTER_VALIDATE_INT
        ]);

        PhotoModel::delete($args['id']);
        $ids = PhotoModel::get_ids($args['poiId']);
        $mainId = PhotoModel::get_main_id($args['poiId']);

        if ($mainId === FALSE && count($ids) > 0) {
            PhotoModel::set_main($ids[0]);
            $mainId = $ids[0];
        }

        return [
            'ids' => $ids,
            'main' => $mainId
        ];
    }

    /**
     * @AjaxCallable
     */
    function rotate() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $dir = filter_input(INPUT_POST, 'dir', FILTER_SANITIZE_STRING);
        $img = PhotoModel::load_original($id);
        if ($dir === 'right') {
            $rotated = image_rotate($img, -90);
        } else if ($dir === 'left') {
            $rotated = image_rotate($img, 90);
        }
        $full = image_resize($rotated, 1600, 900);
        $gallery = image_resize_width($rotated, 580);
        $preview = image_resize_crop($rotated, 216, 216);
        $thumb = image_resize_crop($rotated, 100, 100);
        PhotoModel::update($id, $rotated, $full, $preview, $gallery, $thumb);
        return TRUE;
    }

    /**
     * @AjaxCallable
     */
    function set_main() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        PhotoModel::set_main($id);
        return TRUE;
    }

    /**
     * @AjaxCallable
     */
    function set_description() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        PhotoModel::set_description($id, $description);
        return TRUE;
    }

    /**
     * @AjaxCallable
     */
    function set_offset() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $offset = filter_input(INPUT_POST, 'offset', FILTER_VALIDATE_INT);
        PhotoModel::set_offset($id, $offset);
        return TRUE;
    }

    /**
     * @AjaxCallable
     */
    function get_ids() {
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $ids = PhotoModel::get_ids($poiId);
        return $ids;
    }

    /**
     * @AjaxCallable
     */
    function get_infos() {
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $infos = PhotoModel::get_infos($poiId);
        return $infos;
    }

}
