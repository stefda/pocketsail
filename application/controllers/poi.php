<?php

class POI extends CL_Controller {

    function __construct() {
        parent::__construct();
        require_library('geo/*');
        require_model('POIModel');
        $this->load->helper('geo');
        $this->load->helper('html');
    }

    function add() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->model('POITypeModel');

        $lat = filter_input(INPUT_GET, 'lat', FILTER_VALIDATE_FLOAT);
        $lng = filter_input(INPUT_GET, 'lng', FILTER_VALIDATE_FLOAT);
        $sub = filter_input(INPUT_GET, 'sub', FILTER_SANITIZE_STRING);

        $latLng = new LatLng($lat, $lng);
        $catObject = POITypeModel::catFromSub($sub);

        // Create dummy poi object
        $poi = new stdClass();
        $poi->name = '';
        $poi->label = '';
        $poi->cat = $catObject->id;
        $poi->sub = $sub;
        $poi->nearId = null;
        $poi->countryId = null;
        $poi->latLng = $latLng;
        $poi->border = null;

        $cats = POITypeModel::loadCats();
        $subs = POITypeModel::loadSubs($catObject->id);

        $nears = POIModel::loadNears($latLng);
        $countries = POIModel::loadCountries($latLng);

        $this->assign('poi', $poi);
        $this->assign('nears', $nears);
        $this->assign('countries', $countries);
        $this->assign('cats', $cats);
        $this->assign('subs', $subs);
        $this->assign('attrs', new stdClass());

        $this->load->view('templates/add');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function getSubs() {
        $this->load->model('POITypeModel');
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $subs = POITypeModel::loadSubs($cat);
        return $subs;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function getTemplate() {

        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
        $attrs = filter_input(INPUT_POST, 'attrs', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $poi = new stdClass();
        $poi->name = $name;
        $poi->label = $label;

        $this->assign('poi', $poi);
        $this->assign('attrs', json_decode(json_encode($attrs)));

        return include_edit_template($cat, $sub);
    }

    function edit() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->model('POITypeModel');
        $this->load->model('PhotoModel');

        $poiId = filter_input(INPUT_GET, 'poiId', FILTER_VALIDATE_INT);

        $poiObject = new stdClass();
        $attrsObject = new stdClass();

        if ($poiId !== NULL) {
            $poi = POIModel::load($poiId);
            $poiObject = $poi->toObject();
            $attrsObject = $poi->attrs();
        }

        $cats = POITypeModel::loadCats();
        $subs = POITypeModel::loadSubs($poi->cat());

        $nears = POIModel::loadNears($poi->latLng());
        $countries = POIModel::loadCountries($poi->latLng());

        $this->assign('poi', $poi->toObject());
        $this->assign('nears', $nears);
        $this->assign('countries', $countries);
        $this->assign('cats', $cats);
        $this->assign('subs', $subs);
        $this->assign('attrs', $poi->attrs());
        $this->assign('mainPhotoId', PhotoModel::get_main_id($poi->id()));
        $this->assign('mainPhotoInfo', PhotoModel::get_main_info($poi->id()));

        $this->load->view('templates/edit');
    }

    function view($id) {

        $poi = NULL;

        if (is_numeric($id)) {
            $poi = POIModel::load($id);
        } else {
            $poi = POIModel::loadByUrl($id);
        }

        if ($poi === NULL) {
            error("Place with ID '$id' doesn't seem to exist in Pocketsail. Sorry, folks.");
        }

        $this->load->model('POITypeModel');
        $this->load->model('PhotoModel');

        $poiObject = new stdClass();
        $attrsObject = new stdClass();

        $poiObject->id = $poi->id();
        $poiObject->name = $poi->name();
        $poiObject->cat = $poi->cat();
        $poiObject->sub = $poi->sub();
        $poiObject->latLng = $poi->latLng();
        $poiObject->border = $poi->border();
        $attrsObject = $poi->attrs();

//        $r = sqrt(2 * pow(9, 2));
//        $sw = geo_proximity($poi->latLng(), $r, 215);
//        $ne = geo_proximity($poi->latLng(), $r, 45);
//        $vb = new ViewBounds($sw, $ne);
//        $poiBorder = $vb->toPolygon();

        $nearbys = [];
        $bounds = NULL;

        if ($poi->has_border()) {
            $bounds = LatLngBounds::from_polygon($poi->border());
            $bounds->grow(1.8);
        } else {
            $bounds = new LatLngBounds($poi->latLng());
            $bounds->grow(1.8);
        }

        $ne = $bounds->get_north_east();
        $sw = $bounds->get_south_west();

        $nearbyIds = [$poi->id()];
        $catIds = [];
        $hasGasstation = FALSE;

        $rows = POIModel::load_within_bounds($bounds);
        $rows = array_merge($rows, POIModel::load_neabys($poi->id()));

        foreach ($rows AS $row) {
            if (!in_array($row['id'], $nearbyIds)) {
                if (!isset($nearbys[$row['cat']])) {
                    $nearbys[$row['cat']] = [];
                }
                $row['distance'] = haversine($poi->lat(), $poi->lng(), $row['lat'], $row['lng']);
                $nearbys[$row['cat']][] = $row;
                $nearbyIds[] = $row['id'];
                if ($row['sub'] === 'gasstation') {
                    $hasGasstation = TRUE;
                }
                if (!isset($catIds[$row['cat']])) {
                    $catIds[$row['cat']] = [];
                }
                $catIds[$row['cat']][] = $row['id'];
            }
        }

        if (!$hasGasstation) {
            $bounds = new LatLngBounds($poi->latLng());
            $bounds->grow(32);
            $gasstations = POIModel::load_sub_within_bounds($bounds, 'gasstation');
            foreach ($gasstations AS &$gasstation) {
                $gasstation['distance'] = haversine($poi->lat(), $poi->lng(), $gasstation['lat'], $gasstation['lng']);
                if (!isset($catIds[$gasstation['cat']])) {
                    $catIds[$gasstation['cat']] = [];
                }
                $catIds[$gasstation['cat']][] = $gasstation['id'];
            }
            if (!isset($nearbys['refuelling'])) {
                $nearbys['refuelling'] = [];
            }
            $nearbys['refuelling'] = $gasstations;
        }

        foreach ($nearbys AS &$nearby) {
            aasort($nearby, 'distance');
        }

        $this->assign('catsMap', POITypeModel::cats_name_map());
        $this->assign('subsMap', POITypeModel::subs_name_map());
        $this->assign('poi', $poiObject);
        $this->assign('attrs', $attrsObject);
        $this->assign('nearbys', $nearbys);
        $this->assign('nearbyIds', $nearbyIds);
        $this->assign('catIds', $catIds);
        $this->assign('mainPhotoId', PhotoModel::get_main_id($poi->id()));
        $this->assign('mainPhotoInfo', PhotoModel::get_main_info($poi->id()));
        $this->load->view('templates/view');
    }

}
