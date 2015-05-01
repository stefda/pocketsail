<?php

class Photo extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('image');
        $this->load->model('PhotoModel');
    }

    function index() {
        
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
        $mainId = NULL;
        
        // First photos for this POI, set main!
        if (count($files) === count($ids)) {
            PhotoModel::set_main($ids[0]);
            $mainId = $ids[0];
        }

        echo json_encode([
            'status' => 'OK',
            'ids' => $ids,
            'main' => $mainId
        ]);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function rotate_right() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $img = PhotoModel::load_original($id);
        $rotated = image_rotate($img, -90);
        $full = image_resize($rotated, 1600, 900);
        $gallery = image_resize_width($rotated, 580);
        $preview = image_resize_crop($rotated, 216, 216);
        $thumb = image_resize_crop($rotated, 100, 100);
        PhotoModel::update($id, $rotated, $full, $preview, $gallery, $thumb);
        return TRUE;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function rotate_left() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $img = PhotoModel::load_original($id);
        $rotated = image_rotate($img, 90);
        $full = image_resize($rotated, 1600, 900);
        $gallery = image_resize_width($rotated, 580);
        $preview = image_resize_crop($rotated, 216, 216);
        $thumb = image_resize_crop($rotated, 100, 100);
        PhotoModel::update($id, $rotated, $full, $preview, $gallery, $thumb);
        return TRUE;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function set_main() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        PhotoModel::set_main($id);
        return TRUE;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function set_description() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        PhotoModel::set_description($id, $description);
        return TRUE;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function set_offset() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $offset = filter_input(INPUT_POST, 'offset', FILTER_VALIDATE_INT);
        PhotoModel::set_offset($id, $offset);
        return TRUE;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_ids() {
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $ids = PhotoModel::get_ids($poiId);
        return $ids;
    }
    
    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_infos() {
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $infos = PhotoModel::get_infos($poiId);
        return $infos;
    }

}
