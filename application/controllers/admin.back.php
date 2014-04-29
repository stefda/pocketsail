<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

class Admin extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Geo');
        $this->load->library('geo/LatLng');
        $this->load->library('geo/Polygon');
        $this->load->library('geo/Point');
        $this->load->library('geo/Bounds');
        $this->load->model('POIModel');
//        $this->load->view('admin/header');
    }

    function index() {
        
    }

    function end() {
//        $this->load->view('admin/footer');
    }

    function add_poi() {
//        $this->load->model('POITypeModel');
//        $cats = POITypeModel::loadCats();
//        $this->assign('cats', $cats);
        $this->load->view('admin/map');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function label() {
        $output = null;
        $return_var = null;
        exec('java -jar /home/programs/psw/PocketSailWorks.jar /home/programs/psw/data/', $output, $return_var);
        //print_r($output);
        return $return_var;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=GET
     * @AjaxAsync=TRUE
     */
    function get_subs($id) {
        $this->load->model('POITypeModel');
        $subs = POITypeModel::loadSubs($id);
        return $subs;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_countries() {
        $this->load->library('geo/LatLng');
        $this->load->library('geo/Polygon');
        $this->load->library('geo/Point');
        $this->load->model('POIModel');
        $sLatLng = filter_input(INPUT_POST, 'latLng', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $latLng = LatLng::deserialize($sLatLng);
        return POIModel::find_countries($latLng);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_nearbys() {
        $sLatLng = filter_input(INPUT_POST, 'latLng', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $latLng = LatLng::deserialize($sLatLng);
        return POIModel::find_nearby($latLng);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function save_poi() {

        $this->load->library('geo/LatLng');
        $this->load->library('geo/Point');
        $this->load->library('geo/Polygon');
        $this->load->model('POIModel');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);
        $sLatLng = filter_input(INPUT_POST, 'latLng', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $sBoundary = filter_input(INPUT_POST, 'boundary', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $countryId = filter_input(INPUT_POST, 'countryId', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $nearbyId = filter_input(INPUT_POST, 'nearbyId', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $features = filter_input(INPUT_POST, 'feature', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $latLng = LatLng::deserialize($sLatLng);
        $boundary = Polygon::deserialize($sBoundary);

        if ($id !== false) {
            POIModel::update($id, $nearbyId, $countryId, $name, $label, $cat, $sub, $latLng, $boundary, $features);
            return $id;
        } else {
            return POIModel::add(1, $nearbyId, $countryId, $name, $label, $cat, $sub, $latLng, $boundary, $features);
        }
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function load_poi() {

        $this->load->library('geo/LatLng');
        $this->load->library('geo/Point');
        $this->load->library('geo/Polygon');
        $this->load->model('POIModel');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        return POIModel::load($id);
    }

    function deserialize_features($sFts) {
        $fts = new stdClass();
        foreach ($sFts AS $path => $ftVal) {
            $keys = explode(".", $path);
            $curr = &$fts;
            for ($i = 0; $i < count($keys); $i++) {
                $key = $keys[$i];
                if (!property_exists($curr, $key)) {
                    $curr->{$key} = new stdClass();
                }
                if ($i == count($keys) - 1) {
                    $curr->{$key} = $ftVal;
                } else {
                    $curr = &$curr->{$key};
                }
            }
        }
        return $fts;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function load_pois() {
        $this->load->library('geo/Bounds');
        $this->load->library('geo/LatLng');
        $this->load->library('geo/Polygon');
        $this->load->library('geo/Point');
        $this->load->model('POIModel');
        $sBounds = filter_input(INPUT_POST, 'bounds', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $bounds = Bounds::deserialize($sBounds);
        return POIModel::load_by_bounds($bounds);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_add_poi_dialog() {

        $this->load->model('POITypeModel');

        $sLatLng = filter_input(INPUT_POST, 'latLng', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);

        $latLng = LatLng::deserialize($sLatLng);
        $cats = POITypeModel::loadCats();
        $subs = POITypeModel::loadSubs($cat);
        $countries = POIModel::find_countries($latLng);
        $nearbys = POIModel::find_nearby($latLng);

        $this->assign('id', NULL);
        $this->assign('latLng', $latLng);
        $this->assign('boundary', NULL);
        $this->assign('cat', $cat);
        $this->assign('sub', $sub);
        $this->assign('countryId', 0);
        $this->assign('nearbyId', 0);
        $this->assign('name', '');
        $this->assign('label', '');
        $this->assign('ft', NULL);
        $this->assign('cats', $cats);
        $this->assign('subs', $subs);
        $this->assign('countries', $countries);
        $this->assign('nearbys', $nearbys);

        return $this->load->view('/templates/add/default', false);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_edit_poi_dialog() {

        $this->load->library('geo/LatLng');
        $this->load->model('POIModel');
        $this->load->model('POITypeModel');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $poi = POIModel::load($id);
        $countries = POIModel::find_countries($poi->get_latlng());
        $nearbys = POIModel::find_nearby($poi->get_latlng());

        $this->assign('id', $id);
        $this->assign('latLng', $poi->get_latlng());
        $this->assign('boundary', $poi->get_boundary());
        $this->assign('cat', $poi->get_cat());
        $this->assign('sub', $poi->get_sub());
        $this->assign('countryId', $poi->get_country_id());
        $this->assign('nearbyId', $poi->get_near_id());
        $this->assign('name', $poi->get_name());
        $this->assign('label', $poi->get_label());
        $this->assign('ft', $poi->get_features());
        $this->assign('cats', POITypeModel::loadCats());
        $this->assign('subs', POITypeModel::loadSubs($poi->get_cat()));
        $this->assign('countries', $countries);
        $this->assign('nearbys', $nearbys);

        return $this->load->view('/templates/add/default', false);
    }

    function post() {
        $o = json_encode($_POST);
        print_r(json_decode($o));
    }

}
