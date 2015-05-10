<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

class API extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Security');
        Security::redirectWhenNotSignedIn();
    }

    function suggest() {

        $this->load->library('solr/SolrService');
        $this->load->library('Search');

        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $term = trim($term);

        $term = preg_replace("/^anchor (.*)/", "anchorage $1", $term);
        $term = preg_replace("/^moor (.*)/", "mooring buoys $1", $term);
        $term = preg_replace("/^buoy (.*)/", "mooring buoys $1", $term);
        $term = preg_replace("/^berth (.*)/", "berthing $1", $term);
        $term = preg_replace("/^gas (.*)/", "gas station $1", $term);

        $keywords = [
            'berthing' => ["marina", "mooring", "berths", "jetty"],
            'marina' => ["marina"],
            'marinas' => ["marina"],
            'anchorage' => ["anchorage"],
            'anchorages' => ["anchorage"],
            'mooring buoys' => ["buoys"],
            'buoys' => ["buoys"],
            'anchoring' => ["anchorage", "buoys"],
            'restaurant' => ["restaurant"],
            'restaurants' => ["restaurant"],
            'bar' => ["bar"],
            'bars' => ["bar"],
            'restaurants and bars' => ["restaurant", "bar"],
            'gas station' => ["gasstation"],
            'gas stations' => ["gasstation"],
            'supermarket' => ["supermarket"],
            'supermarkets' => ["supermarket"],
            'cashpoint' => ["cashpoint"],
            'cashpoints' => ["cashpoint"],
            'bakery' => ["bakery"],
            'shopping' => ["supermarket", "minimarket"],
            'shop' => ["supermarket", "minimarket"],
            'shops' => ["supermarket", "minimarket"],
            'beach' => ["beach"]
        ];

        $solr = SolrService::get_instance();
        $search = new Search($solr, $keywords);
        $items = $search->do_search($term);

        echo json_encode($items);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    public function addPoi() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $args = deserialize_input(INPUT_POST, [
            'name' => FILTER_SANITIZE_STRING,
            'label' => FILTER_SANITIZE_STRING,
            'url' => FILTER_SANITIZE_STRING,
            'nearId' => FILTER_VALIDATE_INT,
            'countryId' => FILTER_VALIDATE_INT,
            'cat' => FILTER_SANITIZE_STRING,
            'sub' => FILTER_SANITIZE_STRING,
            'latLng' => 'LatLng',
            'border' => 'Polygon',
            'attrs' => [
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY
            ]
        ]);

        POIModel::addNew($args['url'], $args['nearId'], $args['countryId'], 1, $args['name'], $args['label'], $args['cat'], $args['sub'], $args['latLng'], $args['border'], $args['attrs']);
        return TRUE;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    public function updatePoi() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

//        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
//        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
//        $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
//        $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING);
//        $nearId = filter_input(INPUT_POST, 'nearId', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
//        $countryId = filter_input(INPUT_POST, 'countryId', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
//        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
//        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);
//        $latLngWKT = filter_input(INPUT_POST, 'latLng', FILTER_SANITIZE_STRING);
//        $borderWKT = filter_input(INPUT_POST, 'border', FILTER_SANITIZE_STRING);
//        $attrs = filter_input(INPUT_POST, 'attrs', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $args = deserialize_input(INPUT_POST, [
            'poiId' => FILTER_VALIDATE_INT,
            'name' => FILTER_SANITIZE_STRING,
            'label' => FILTER_SANITIZE_STRING,
            'url' => FILTER_SANITIZE_STRING,
            'nearId' => FILTER_VALIDATE_INT,
            'countryId' => FILTER_VALIDATE_INT,
            'cat' => FILTER_SANITIZE_STRING,
            'sub' => FILTER_SANITIZE_STRING,
            'latLng' => 'LatLng',
            'border' => 'Polygon',
            'attrs' => [
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY
            ]
        ]);

//        POIModel::update($id, $url, $nearId, $countryId, 1, $name, $label, $cat, $sub, $latLng, $border, $attrs);
        POIModel::update($args['poiId'], $args['url'], $args['nearId'], $args['countryId'], 1, $args['name'], $args['label'], $args['cat'], $args['sub'], $args['latLng'], $args['border'], $args['attrs']);

        return TRUE;
    }

    /**
     * @param int $poiId
     * @return stdClass
     * @AjaxCallable=TRUE
     * @AjaxMethod=GET
     * @AjaxAsync=TRUE
     */
    public function get_poi_info($poiId) {

        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->model('LabelModel');

        $poi = POIModel::load($poiId);
        return $poi->toObject();
    }

    /**
     * @param int $poiId
     * @return stdClass
     * @AjaxCallable=TRUE
     * @AjaxMethod=GET
     * @AjaxAsync=TRUE
     */
    public function get_poi_info_box($poiId) {

        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->model('LabelModel');

        $poi = POIModel::load($poiId);

        $this->assign('poi', $poi);
        $html = $this->load->view('templates/infobox', FALSE);
        return [
            'html' => $html,
            'latLng' => $poi->latLng()
        ];
    }

}
