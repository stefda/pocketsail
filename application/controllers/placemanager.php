<?php

class PlaceManager extends CL_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->library('Point');
        $this->load->library('Polygon');
        $this->load->model('Place');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function add_place() {
        
        $class = $_POST['class'];
        $type = $_POST['type'];
        $name = $_POST['name'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $points = key_exists('boundary', $_POST) ? $_POST['boundary'] : [];
        $data = $_POST['data'];
        $userID = 1;
        
        $location = new Point($lat, $lng);
        $boundary = new Polygon($points);
        
        $ID = Place::add($class, $type, $name, $location, $boundary, $data, $userID);
        $p = Place::load($ID);
        
        return $p->to_object();
    }
    
    function get_place($ID) {
        $p = Place::load($ID);
        echo json_encode($p->get_full_info());
    }
}

