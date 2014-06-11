<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function menu() {
        $this->load->view('menu');
    }

    function edit() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $poiId = filter_input(INPUT_GET, 'poiId', FILTER_VALIDATE_INT);
        $lat = filter_input(INPUT_GET, 'lat', FILTER_VALIDATE_FLOAT);
        $lng = filter_input(INPUT_GET, 'lng', FILTER_VALIDATE_FLOAT);
        $cat = filter_input(INPUT_GET, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_GET, 'sub', FILTER_SANITIZE_STRING);

        $poiObject = new stdClass();
        $attrsObject = new stdClass();

        if ($poiId !== NULL) {
            $poi = POIModel::load($poiId);
            $poiObject = $poi->toObject();
            $attrsObject = $poi->attributes();
        } else {
            $poiObject->id = 0;
            $poiObject->name = '';
            $poiObject->cat = $cat;
            $poiObject->sub = $sub;
            $poiObject->latLng = new LatLng($lat, $lng);
            $poiObject->border = null;
        }

        $this->assign('poi', $poiObject);
        $this->assign('attrs', $attrsObject);
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

    function pages() {
        $this->load->view('pages');
    }

    function suggest() {
        $this->load->view('suggest');
    }

    function fulltext() {
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $term = strtolower(trim($term));
        $this->load->library('solr/SolrService');
        $solr = SolrService::get_instance();
        $res = $solr->query("fulltext:($term)", 10);
        $this->assign('term', $term);
        $this->assign('numFound', $res->num_found());
        $this->assign('numDocs', $res->num_docs());
        $this->assign('docs', $res->docs());
        $this->load->view('fulltext');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function save_data() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);
        $latLngWKT = filter_input(INPUT_POST, 'latLng', FILTER_SANITIZE_STRING);
        $borderWKT = filter_input(INPUT_POST, 'border', FILTER_SANITIZE_STRING);
        $attrs = filter_input(INPUT_POST, 'attrs', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $latLng = LatLng::fromWKT($latLngWKT);
        $border = Polygon::fromWKT($borderWKT);

        POIModel::update($id, 1, 1, $name, $name, $cat, $sub, $latLng, $border, $attrs);
    }

}
