<?php

class POI extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Security');
        Security::redirectWhenNotSignedIn();
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

        $this->load->view('templates/edit');
    }

    function view() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $poiId = filter_input(INPUT_GET, 'poiId', FILTER_VALIDATE_INT);

        $poiObject = new stdClass();
        $attrsObject = new stdClass();

        $poi = POIModel::load($poiId);
        $poiObject->id = $poi->id();
        $poiObject->name = $poi->name();
        $poiObject->cat = $poi->cat();
        $poiObject->sub = $poi->sub();
        $poiObject->latLng = $poi->latLng();
        $poiObject->border = $poi->border();
        $attrsObject = $poi->attributes();

        $r = sqrt(2 * pow(9, 2));
        $sw = Geo::proximity($poi->latLng(), $r, 215);
        $ne = Geo::proximity($poi->latLng(), $r, 45);
        $vb = new ViewBounds($sw, $ne);
        $poiBorder = $vb->toPolygon();

        $near = POIModel::loadByBorder($poiBorder, ['restaurant', 'supermarket', 'gasstation', 'anchorage', 'buoys'], 198);
        $nearSorted = [];
        $nearSortedIds = [];

        // Separate into subcategories
        foreach ($near AS $poiTo) {
            $sub = $poiTo->sub();
            if (!array_key_exists($sub, $nearSorted)) {
                $nearSorted[$sub] = [];
                $nearSortedIds[$sub] = [];
            }
            $nearSorted[$sub][] = [
                'poi' => $poiTo->toObject(),
                'dist' => Geo::haversine($poi->latLng(), $poiTo->latLng())
            ];
            $nearSortedIds[$sub][] = $poiTo->id();
        }

        // Sort each subcategory by disance
        foreach ($nearSorted AS &$near) {
            aasort($near, 'dist');
        }

        $this->assign('poi', $poiObject);
        $this->assign('attrs', $attrsObject);
        $this->assign('near', (object) $nearSorted);
        $this->assign('nearIds', $nearSortedIds);
        $this->load->view('templates/view');
    }

}
