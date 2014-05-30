<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

class Map extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('geo/*');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function loadData() {

        $vBoundsWKT = filter_input(INPUT_POST, 'vBounds', FILTER_SANITIZE_STRING);
        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);
        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $flags = filter_input(INPUT_POST, 'flags', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $vBounds = ViewBounds::fromWKT($vBoundsWKT);
        $res = [];

        if ($flags === NULL) {
            $flags = [];
        }

        if (in_array('panToPoi', $flags)) {
            $center = $vBounds->getCenter();
            $vBounds->setCenter(new LatLng($center->lat(), $center->lng() + 10));
            $vBounds->zoomOut();
            $res['center'] = $vBounds->getCenter()->toWKT();
            $res['zoom'] = --$zoom;
        }

        if (in_array('zoomToTypes', $flags)) {
            echo "change vBounds to include types\n";
        }

        if (in_array('poiInfo', $flags)) {
            echo "load poi info\n";
        }

        if (in_array('poiCardView', $flags)) {
            echo "load poi card view\n";
        }
        
        $res['labels'] = [];

        return $res;
    }
    
    public function loadDynamicLabels() {
        
    }

}
