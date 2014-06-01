<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

class Map extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->model('LabelModel');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function loadData() {

        // Required params
        $vBoundsWKT = filter_input(INPUT_POST, 'vBounds', FILTER_SANITIZE_STRING);
        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);

        // Optional params, need to normalise if not present
        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $flags = filter_input(INPUT_POST, 'flags', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $vBounds = ViewBounds::fromWKT($vBoundsWKT);

        // Normalise parameters
        if ($types === NULL) {
            $types = [];
        }

        if ($poiId === NULL) {
            $poiId = 0;
        }

        if ($flags === NULL) {
            $flags = [];
        }

        // Prepare result object
        $res = [
            'labels' => [],
            'flags' => []
        ];

        if (in_array('poiInfo', $flags) || in_array('poiCard', $flags) || in_array('panToPoi', $flags)) {

            $poi = POIModel::load($poiId);
            $res['poi'] = [];

            // Load poi info
            if (in_array('poiInfo', $flags)) {
                $res['poi']['info'] = $poi->info();
            }

            // Load poi card
            if (in_array('poiCard', $flags)) {
                $res['poi']['card'] = "<div>Card</div>";
                $res['flags'][] = "showCard";
            }

            // Pan to poi and fit border or adjust zoom accordingly
            if (in_array('panToPoi', $flags)) {
                $border = $poi->border();
                if ($border === NULL) {
                    $vBounds->setCenter($poi->latLng());
                    $vBounds->changeZoom(14 - $zoom);
                    $zoom = 14;
                } else {
                    $borderBounds = ViewBounds::fromPolygon($border);
                    print_r($borderBounds);
                    //$vBounds->fitBounds($borderBounds, $zoom);
                    $vBounds->fitBounds($borderBounds);
                    print_r($vBounds);
                }
                $res['flags'][] = "panToCenter";
            }
        }

        if ($types !== NULL) {
            $bounds = $vBounds->toBounds();
            if (in_array('zoomToTypes', $flags)) {
                $guard = 18;
                while (!LabelModel::typesWithinBounds($bounds, $types, $poiId) && --$guard > 0) {
                    $vBounds->zoomOut();
                    $bounds = $vBounds->toBounds();
                    $zoom--;
                }
                $res['flags'][] = "panToCenter";
            }
            $res['labels'] = LabelModel::loadDynamicByBounds($bounds, $types, $poiId);
            $res['flags'][] = "doLabelling";
        }

        if ($types !== NULL && count($types) > 0) {
            $bounds = $vBounds->toBounds();
            $res['labels'] = array_merge($res['labels'], LabelModel::loadStaticDynamicByBounds($bounds, $zoom, 0, $types));
        } else {
            $bounds = $vBounds->toBounds();
            $res['labels'] = LabelModel::loadStaticByBounds($bounds, $zoom, $poiId);
        }

        $res['center'] = $vBounds->getCenter()->toWKT();
        $res['zoom'] = $zoom;

        return $res;
    }

    public function loadDynamicLabels() {
        
    }

}
