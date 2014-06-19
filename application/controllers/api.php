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

        // Parse term from the input
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $term = trim($term);
        
        $term = preg_replace("/^anchor (.*)/", "anchorage $1", $term);
        $term = preg_replace("/^moor (.*)/", "mooring buoys $1", $term);
        $term = preg_replace("/^buoy (.*)/", "mooring buoys $1", $term);
        $term = preg_replace("/^berth (.*)/", "berthing $1", $term);
        $term = preg_replace("/^gas (.*)/", "gas station $1", $term);

        $keywords = [
            'berthing' => ["marina", "mooring"],
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
            'shops' => ["supermarket", "minimarket"]
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

        // Helper functions
        function addFlag(&$res, $flag) {
            in_array($flag, $res['flags']) ? null : $res['flags'][] = $flag;
        }

        function hasFlag($res, $flag) {
            return in_array($flag, $res);
        }

        function addLabels(&$res, $labels) {
            $res['labels'] = array_merge($res['labels'], $labels);
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

        if ($poiId !== 0 && !hasFlag($flags, 'excludePoiLabel')) {

            $poi = POIModel::load($poiId);
            $res['labels'][] = LabelModel::loadDynamic($poiId);
            $res['poi'] = [];
            addFlag($res, 'doLabelling');

            // Load poi info
            if (hasFlag($flags, 'poiInfo')) {
                $res['poi']['info'] = $poi->toObject();
            }

            // Load poi card
            if (hasFlag($flags, 'poiCard')) {
                $res['poi']['card'] = "<div>Card</div>";
                addFlag($res, 'showCard');
            }

            // Pan to poi and fit border or adjust zoom accordingly
            if (hasFlag($flags, 'panToPoi')) {
                $border = $poi->border();
                if ($border === NULL) {
                    $vBounds->setCenter($poi->latLng());
                    $vBounds->changeZoom(14 - $zoom);
                    $zoom = 14;
                } else {
                    $borderBounds = ViewBounds::fromPolygon($border);
                    $vBounds->fitBounds($borderBounds, $zoom);
                }
                addFlag($res, 'panToCenter');
            }
        }

        if (count($poiIds) > 0) {

            // Load labels by poiIds
            addLabels($res, LabelModel::loadDynamicByIds($poiIds));

            if (hasFlag($flags, 'zoomToPois')) {

                // Load pois for their positions and borders
                $pois = POIModel::loadByIds($poiIds);

                // Expand pois
                if ($poiId !== 0) {
                    $poi = POIModel::load($poiId);
                    $pois = array_merge($pois, [$poi]);
                }

                // Initialise bounds and extend by all pois
                $bounds = new ViewBounds($pois[0]->latLng(), $pois[0]->latLng());
                for ($i = 1; $i < count($pois); $i++) {
                    if ($pois[$i]->border() === NULL) {
                        $bounds->extendByLatLng($pois[$i]->latLng());
                    } else {
                        $borderBounds = ViewBounds::fromPolygon($pois[$i]->border());
                        $bounds->extendByBounds($borderBounds);
                    }
                }
                $vBounds->fitBounds($bounds, $zoom);
                $bounds->buffer(30, 30, $zoom);
                $vBounds->fitBounds($bounds, $zoom);
            }
            addFlag($res, 'panToCenter');
            addFlag($res, 'doLabelling');
        }

        if (count($types) > 0) {
            $bounds = $vBounds->toBounds();
            if (in_array('zoomToTypes', $flags)) {
                $guard = 18;
                while (!LabelModel::typesWithinBounds($bounds, $types, $poiId) && --$guard > 0) {
                    $vBounds->zoomOut();
                    $bounds = $vBounds->toBounds();
                    $zoom--;
                    addFlag($res, 'panToCenter');
                }
            }
            addLabels($res, LabelModel::loadDynamicByBounds($bounds, $types, $poiId));
            addFlag($res, 'doLabelling');
        }

        if ($poiId !== 0 || $types !== NULL && count($types) > 0) {
            $bounds = $vBounds->toBounds();
            $exceptIds = array_merge($poiIds, [$poiId]);
            addLabels($res, LabelModel::loadStaticDynamicByBounds($bounds, $zoom, $exceptIds, $types));
        } else {
            $bounds = $vBounds->toBounds();
            addLabels($res, LabelModel::loadStaticByBounds($bounds, $zoom));
        }

        if (hasFlag($flags, 'newPois')) {
            // Parameter '1' TO BE changed to logged-in user's id
            $bounds = $vBounds->toBounds();
            $res['new'] = LabelModel::loadNew($bounds, 1);
        }

        $res['center'] = $vBounds->getCenter()->toWKT();
        $res['zoom'] = $zoom;

        return $res;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    public function addPoi() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
        $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING);
        $nearId = filter_input(INPUT_POST, 'nearId', FILTER_VALIDATE_INT);
        $countryId = filter_input(INPUT_POST, 'countryId', FILTER_VALIDATE_INT);
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);
        $latLngWKT = filter_input(INPUT_POST, 'latLng', FILTER_SANITIZE_STRING);
        $borderWKT = filter_input(INPUT_POST, 'border', FILTER_SANITIZE_STRING);
        $attrs = filter_input(INPUT_POST, 'attrs', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $latLng = LatLng::fromWKT($latLngWKT);
        $border = Polygon::fromWKT($borderWKT);

        POIModel::addNew($url, $nearId, $countryId, 1, $name, $label, $cat, $sub, $latLng, $border, $attrs);

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

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
        $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING);
        $nearId = filter_input(INPUT_POST, 'nearId', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $countryId = filter_input(INPUT_POST, 'countryId', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);
        $latLngWKT = filter_input(INPUT_POST, 'latLng', FILTER_SANITIZE_STRING);
        $borderWKT = filter_input(INPUT_POST, 'border', FILTER_SANITIZE_STRING);
        $attrs = filter_input(INPUT_POST, 'attrs', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $latLng = LatLng::fromWKT($latLngWKT);
        $border = Polygon::fromWKT($borderWKT);

        //POIModel::addNew(1, $nearId, $countryId, $name, $label, $cat, $sub, $latLng, $border, $attrs);
        POIModel::update($id, $url, $nearId, $countryId, 1, $name, $label, $cat, $sub, $latLng, $border, $attrs);

        return TRUE;
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    public function doLabelling() {
        
    }

}
