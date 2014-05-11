<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

class API extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Geo');
        $this->load->library('geo/LatLng');
        $this->load->library('geo/Bounds');
        $this->load->library('geo/Point');
        $this->load->library('geo/Polygon');
    }

    function search() {
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $this->load->model("Search");
        $this->load->library('solr/SolrService');
        $solr = SolrService::get_instance();
        $s = new Search($solr);
        $res = $s->search($term);
        echo json_encode($res);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_labels() {

        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);
        $bbox = (object) filter_input(INPUT_POST, 'bbox', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $loadPoiInfo = filter_input(INPUT_POST, 'loadPoiInfo', FILTER_VALIDATE_BOOLEAN);

        if ($zoom === FALSE || $bbox === FALSE || $types === FALSE || $poiId === FALSE) {
            show_error("API::get_labels ERROR: Wrong parameters");
        }

        $this->load->model('LabelModel');
        $this->load->model('POIModel');

        // Prepare result object
        $res = new stdClass();
        $res->labels = [];
        $res->lType = 'static';

        // Prepare helper variables
        $priority = ['country', 'region', 'town', 'archipelago', 'island'];
        $dynamicTypes = $types;

        if ($poiId !== NULL && $poiId !== 0) {
            $res->labels[] = LabelModel::load_label($poiId);
            $res->lType = 'poi';
        }

        if ($types !== NULL && count($types) > 0) {
            //$res->labels = array_merge($res->labels, LabelModel::load_static_by_types($zoom, $bbox, $priority, $poiId));
            $res->labels = array_merge($res->labels, LabelModel::load_dynamic($bbox, $types, $poiId));
            //$dynamicTypes = array_merge($types, $priority);
            $dynamicTypes = $types;
            $res->lType = 'dynamic';
        }

        // Load static labels (with static or dynamic descriptors)
        $res->labels = array_merge($res->labels, LabelModel::load_static($zoom, $bbox, $dynamicTypes, $poiId, $res->lType));

        if ($loadPoiInfo) {
            $poi = POIModel::load($poiId);
            $res->poiInfo = new stdClass();
            $res->poiInfo->exposition = @$poi->get_features()->exposition;
            $res->poiInfo->html = $this->get_short_info($poiId);
        }
        
        $bounds = new Bounds($bbox->n, $bbox->e, $bbox->s, $bbox->w);
        $res->new = POIModel::load_new_by_bounds($bounds);
        return $res;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function get_mcz() {

        $this->load->model('LabelModel');

        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);
        $sBounds = filter_input(INPUT_POST, 'bounds', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $sPoiLatLng = filter_input(INPUT_POST, 'poiLatLng', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);
        $sPoiBounds = filter_input(INPUT_POST, 'poiBounds', FILTER_VALIDATE_FLOAT, FILTER_REQUIRE_ARRAY);

        if ($zoom === NULL || $sBounds === NULL || $types === NULL) {
            show_error("API::get_mcz ERROR: Missing parameters");
        }

        if (($sPoiLatLng !== NULL && $sPoiBounds !== NULL) || $zoom === FALSE || $sPoiLatLng === FALSE || $sBounds === FALSE || $types === FALSE) {
            show_error("API::get_mcz ERROR: Wrong parameters");
        }

        $bounds = Bounds::deserialize($sBounds);
        $poiLatLng = NULL;

        if ($sPoiLatLng !== NULL) {
            $poiLatLng = LatLng::deserialize($sPoiLatLng);
            $bounds->set_center($poiLatLng);
            $bounds->set_zoom($zoom, $zoom = 16);
        }

        if ($sPoiBounds !== NULL) {
            $poiBounds = Bounds::deserialize($sPoiBounds);
            $poiLatLng = $poiBounds->get_center();
            $bounds->set_center($poiLatLng);
            $zoom = $bounds->fit($poiBounds, $zoom);
        }

        $newZoom = LabelModel::get_mcz($zoom, $bounds, $types);
        return [
            'latLng' => $poiLatLng !== NULL ? $poiLatLng->serialize() : NULL,
            'zoom' => $newZoom
        ];
    }
    
    public function get_short_info($id) {
        $this->load->model('POIModel');
        $this->load->library('Template');
        $poi = POIModel::load($id);
        $html = Template::short($poi->get_info(), $poi->get_sub());
        return $html;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    public function get_marker_info() {
        $id = filter_input(INPUT_POST, 'id');
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT * FROM `poi` WHERE `id` = $id");
        $o = $mysql->fetch_object($r);
        $this->assign('name', $o->name);
        $this->assign('sub', $o->sub);
        $html = $this->load->view('template/info', FALSE);
        return $html;
    }

}
