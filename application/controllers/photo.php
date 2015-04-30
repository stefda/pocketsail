<?php

class Photo extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        echo "init";
    }

    function upload() {

        $this->load->helper('image');
        $this->load->model('PhotoModel');

        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $path = $_FILES['photo']['tmp_name'];

        $img = image_create($path);
        $full = image_resize($img, 1600, 900);
        $preview = image_resize_crop($img, 580, 200);
        $gallery = image_resize_crop($img, 230, 230);
        $thumb = image_resize_crop($img, 100, 100);
        $photo = new PhotoModel($poiId);
        $photo->insert($title, $description, $full, $preview, $gallery, $thumb);

        echo json_encode([
            'status' => 'OK'
        ]);
    }

}
