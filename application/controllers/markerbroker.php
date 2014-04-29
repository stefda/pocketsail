<?php

class MarkerBroker extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Geo');
        $this->load->library('LatLng');
        $this->load->library('Point');
        $this->load->library('Polygon');
        $this->load->model('MarkerModel');
    }

    /**
     * @AjaxCallable
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function load_by_sub() {

        if (!post_keys_exist('n', 'e', 's', 'w', 'zoom', 'sub')) {
            show_error('Missing query parameters.');
        }

        $n = filter_input(INPUT_POST, 'n');
        $e = filter_input(INPUT_POST, 'e');
        $s = filter_input(INPUT_POST, 's');
        $w = filter_input(INPUT_POST, 'w');
        $zoom = filter_input(INPUT_POST, 'zoom');
        $sub = filter_input(INPUT_POST, 'sub');

        $bbox = Geo::create_bbox($n, $e, $s, $w);
        $markers = MarkerModel::load_by_bbox_except($bbox, $zoom, $sub);
        $feature = MarkerModel::load_by_sub($bbox, $sub);

        return (object) [
                    'markers' => $markers,
                    'feature' => $feature
        ];
    }

    /**
     * @AjaxCallable
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function load_by_bbox() {

        if (!post_keys_exist('n', 'e', 's', 'w', 'zoom')) {
            show_error('Missing query parameters.');
        }

        $n = filter_input(INPUT_POST, 'n');
        $e = filter_input(INPUT_POST, 'e');
        $s = filter_input(INPUT_POST, 's');
        $w = filter_input(INPUT_POST, 'w');
        $zoom = filter_input(INPUT_POST, 'zoom');

        $bbox = Geo::create_bbox($n, $e, $s, $w);
        return MarkerModel::load_by_bbox($bbox, $zoom);
    }

}
