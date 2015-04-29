<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

class API2 extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Security');
        Security::redirectWhenNotSignedIn();
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    public function loadData() {

        $this->load->library('geo/*');
        $this->load->library('MapManager');
        $this->load->model('POIModel');
        $this->load->model('LabelModel');
        $this->load->helper('html');
        
        $width = filter_input(INPUT_POST, 'width', FILTER_VALIDATE_INT);
        $height = filter_input(INPUT_POST, 'height', FILTER_VALIDATE_INT);
        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);
        $centerWKT = filter_input(INPUT_POST, 'center', FILTER_SANITIZE_STRING);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING);
        $ids = filter_input(INPUT_POST, 'ids', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        
        $center = LatLng::fromWKT($centerWKT);
        $ids = $ids === NULL ? [] : $ids;
        $types = $types === NULL ? [] : $types;

        $mm = new MapManager();

        if ($action == 'normal') {
            return $mm->normal($width, $height, $zoom, $center, $id, $url, $ids, $types);
        }
        
        if ($action == 'click') {
            return $mm->click($width, $height, $zoom, $id, $url);
        }
        
        if ($action == 'search') {
            return $mm->search($width, $height, $zoom, $center, $id, $url, $types);
        }
        
        if ($action == 'hash') {
            return $mm->hash($width, $height, $zoom, $center, $id, $url, $ids, $types);
        }
    }

}
