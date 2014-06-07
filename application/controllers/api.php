<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

class API extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function suggest() {

        $this->load->library('solr/SolrService');
        $this->load->library('Search');

        // Parse term from the input
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

        $keywords = [
            'berthing' => ["marina", "mooring"],
            'anchoring' => ["anchorage", "buoys"],
            'marina' => ["marina"],
            'marinas' => ["marina"],
            'anchorage' => ["anchorage"],
            'anchorages' => ["anchorage"],
            'mooring buoys' => ["buoys"],
            'buoys' => ["buoys"],
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
            'shopping' => ["supermarket", "cashpoint"]
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
    public function loadData() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->model('LabelModel');

        // Helper function to set flags
        function addFlag(&$res, $flag) {
            in_array($flag, $res['flags']) ? null : $res['flags'][] = $flag;
        }

        // Required params
        $vBoundsWKT = filter_input(INPUT_POST, 'vBounds', FILTER_SANITIZE_STRING);
        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);

        // Optional params, need to normalise if not present
        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
        $poiIds = filter_input(INPUT_POST, 'poiIds', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        $flags = filter_input(INPUT_POST, 'flags', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $vBounds = ViewBounds::fromWKT($vBoundsWKT);

        // Normalise parameters
        if ($types === NULL) {
            $types = [];
        }

        if ($poiId === NULL) {
            $poiId = 0;
        }

        if ($poiIds === NULL) {
            $poiIds = [];
        }

        if ($flags === NULL) {
            $flags = [];
        }

        // Prepare result object
        $res = [
            'labels' => [],
            'flags' => []
        ];

        if ($poiId !== 0 && !in_array('excludePoiLabel', $flags)) {

            $poi = POIModel::load($poiId);
            $res['labels'][] = LabelModel::loadDynamic($poiId);
            $res['poi'] = [];
            addFlag($res, "doLabelling");

            // Load poi info
            if (in_array('poiInfo', $flags)) {
                $res['poi']['info'] = $poi->info();
            }

            // Load poi card
            if (in_array('poiCard', $flags)) {
                $res['poi']['card'] = "<div>Card</div>";
                addFlag($res, "showCard");
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
                    $vBounds->fitBounds($borderBounds, $zoom);
                }
                addFlag($res, "panToCenter");
            }
        }

        if (count($poiIds) > 0) {

            // Load labels by poiIds
            $res['labels'] = array_merge($res['labels'], LabelModel::loadDynamicByIds($poiIds));

            if (in_array('zoomToPois', $flags)) {

                // Load pois for their positions and borders
                $pois = POIModel::loadByIds($poiIds);

                // Initialise bounds
                if ($poiId !== 0) {
                    $poi = POIModel::load($poiId);
                    $bounds = new ViewBounds($poi->latLng(), $poi->latLng());
                } else {
                    $bounds = new ViewBounds();
                }

                foreach ($pois AS $poi) {
                    if ($poi->border() === NULL) {
                        $bounds->extendByLatLng($poi->latLng());
                    } else {
                        $borderBounds = ViewBounds::fromPolygon($poi->border());
                        $bounds->extendByBounds($borderBounds);
                    }
                }
                $vBounds->fitBounds($bounds, $zoom);
                $tempBounds = clone($vBounds);
//                $tempBounds = new ViewBounds($vBounds->getSouthWest(), $vBounds->getNorthEast());
                $tempBounds->buffer(1000, 1000, $zoom);
//                print_r($vBounds);
//                print_r($tempBounds);
                $vBounds->fitBounds($tempBounds, $zoom);
//                echo $zoom;
            }
            addFlag($res, "panToCenter");
            addFlag($res, "doLabelling");
        }

        if (count($types) > 0) {
            $bounds = $vBounds->toBounds();
            if (in_array('zoomToTypes', $flags)) {
                $guard = 18;
                while (!LabelModel::typesWithinBounds($bounds, $types, $poiId) && --$guard > 0) {
                    $vBounds->zoomOut();
                    $bounds = $vBounds->toBounds();
                    $zoom--;
                    in_array('panToCenter', $res['flags']) ? null : $res['flags'][] = 'panToCenter';
                }
                $tempBounds = clone($vBounds);
//                $tempBounds->buffer(10, 10, $zoom);
//                $vBounds->fitBounds($tempBounds, $zoom);
            }
            $res['labels'] = array_merge($res['labels'], LabelModel::loadDynamicByBounds($bounds, $types, $poiId));
            addFlag($res, "doLabelling");
        }

        if ($poiId !== 0 || $types !== NULL && count($types) > 0) {
            $bounds = $vBounds->toBounds();
            $exceptIds = array_merge($poiIds, [$poiId]);
            $res['labels'] = array_merge($res['labels'], LabelModel::loadStaticDynamicByBounds($bounds, $zoom, $exceptIds, $types));
        } else {
            $bounds = $vBounds->toBounds();
            $res['labels'] = array_merge($res['labels'], LabelModel::loadStaticByBounds($bounds, $zoom));
        }

        $res['center'] = $vBounds->getCenter()->toWKT();
        $res['zoom'] = $zoom;

        return $res;
    }

//    /**
//     * @AjaxCallable=TRUE
//     * @AjaxMethod=POST
//     * @AjaxAsync=TRUE
//     */
//    public function loadData() {
//
//        $this->load->library('geo/*');
//        $this->load->model('POIModel');
//        $this->load->model('LabelModel');
//
//        // Helper function to set flags
//        function addFlag(&$res, $flag) {
//            in_array($flag, $res['flags']) ? null : $res['flags'][] = $flag;
//        }
//
//        // Required params
//        $vBoundsWKT = filter_input(INPUT_POST, 'vBounds', FILTER_SANITIZE_STRING);
//        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);
//
//        // Optional params, need to normalise if not present
//        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
//        $poiId = filter_input(INPUT_POST, 'poiId', FILTER_VALIDATE_INT);
//        $flags = filter_input(INPUT_POST, 'flags', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
//
//        $vBounds = ViewBounds::fromWKT($vBoundsWKT);
//
//        // Normalise parameters
//        if ($types === NULL) {
//            $types = [];
//        }
//
//        if ($poiId === NULL) {
//            $poiId = 0;
//        }
//
//        if ($flags === NULL) {
//            $flags = [];
//        }
//
//        // Prepare result object
//        $res = [
//            'labels' => [],
//            'flags' => []
//        ];
//
//        if ($poiId !== 0 && !in_array('excludePoiLabel', $flags)) {
//
//            $poi = POIModel::load($poiId);
//            $res['labels'][] = LabelModel::loadDynamic($poiId);
//            $res['poi'] = [];
//            addFlag($res, "doLabelling");
//
//            // Load poi info
//            if (in_array('poiInfo', $flags)) {
//                $res['poi']['info'] = $poi->info();
//            }
//
//            // Load poi card
//            if (in_array('poiCard', $flags)) {
//                $res['poi']['card'] = "<div>Card</div>";
//                addFlag($res, "showCard");
//            }
//
//            // Pan to poi and fit border or adjust zoom accordingly
//            if (in_array('panToPoi', $flags)) {
//                $border = $poi->border();
//                if ($border === NULL) {
//                    $vBounds->setCenter($poi->latLng());
//                    $vBounds->changeZoom(14 - $zoom);
//                    $zoom = 14;
//                } else {
//                    $borderBounds = ViewBounds::fromPolygon($border);
//                    $vBounds->fitBounds($borderBounds, $zoom);
//                }
//                addFlag($res, "panToCenter");
//            }
//        }
//
//        if (count($types) > 0) {
//            $bounds = $vBounds->toBounds();
//            if (in_array('zoomToTypes', $flags)) {
//                $guard = 18;
//                while (!LabelModel::typesWithinBounds($bounds, $types, $poiId) && --$guard > 0) {
//                    $vBounds->zoomOut();
//                    $bounds = $vBounds->toBounds();
//                    $zoom--;
//                    in_array('panToCenter', $res['flags']) ? null : $res['flags'][] = 'panToCenter';
//                }
//            }
//            $res['labels'] = array_merge($res['labels'], LabelModel::loadDynamicByBounds($bounds, $types, $poiId));
//            addFlag($res, "doLabelling");
//        }
//
//        if ($poiId !== 0 || $types !== NULL && count($types) > 0) {
//            $bounds = $vBounds->toBounds();
//            $res['labels'] = array_merge($res['labels'], LabelModel::loadStaticDynamicByBounds($bounds, $zoom, $poiId, $types));
//        } else {
//            $bounds = $vBounds->toBounds();
//            $res['labels'] = array_merge($res['labels'], LabelModel::loadStaticByBounds($bounds, $zoom, $poiId));
//        }
//
//        $res['center'] = $vBounds->getCenter()->toWKT();
//        $res['zoom'] = $zoom;
//
//        return $res;
//    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    public function addPoi() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $latLngWKT = filter_input(INPUT_POST, 'latLng', FILTER_SANITIZE_STRING);
        $attrs = filter_input(INPUT_POST, 'attrs', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $poi = POIModel::load($id);
        $label = $poi->label();
        $cat = $poi->cat();
        $sub = $poi->sub();
        $latLng = LatLng::fromWKT($latLngWKT);
        $border = $poi->border();
        $countryId = $poi->countryId();
        $nearId = $poi->nearId();

        POIModel::update($id, $nearId, $countryId, $name, $label, $cat, $sub, $latLng, $border, $attrs);
    }

}
