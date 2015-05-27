<?php

define('MAP_DEFAULT_ZOOM', 15);

class Map extends CL_Controller {

    function __construct() {
        parent::__construct();
        require_library('geo/*');
        require_model('POIModel');
        require_model('LabelModel');
        $this->load->helper('html');
        $this->load->helper('geo');
    }

    /**
     * @AjaxCallable
     */
    function load_data() {

        $args = deserialize_input(INPUT_POST, [
            'action' => FILTER_SANITIZE_STRING,
            'mode' => FILTER_SANITIZE_STRING,
            'poiId' => FILTER_VALIDATE_INT,
            'poiIds' => [
                'filter' => FILTER_VALIDATE_INT,
                'flags' => FILTER_REQUIRE_ARRAY
            ],
            'poiUrls' => [
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY
            ],
            'types' => [
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY
            ],
            'poiUrl' => FILTER_SANITIZE_STRING,
            'bounds' => 'LatLngBounds',
            'zoom' => FILTER_VALIDATE_INT,
            'width' => FILTER_VALIDATE_INT,
            'height' => FILTER_VALIDATE_INT
        ]);

        $action = isset($args['action']) ? $args['action'] : 'default';

        switch ($action) {
            case 'default': {
                    return $this->load_default($args);
                }
            case 'click': {
                    return $this->load_click($args);
                }
            case 'search': {
                    return $this->load_search($args);
                }
            case 'hash': {
                    return $this->load_hash($args);
                }
            case 'edit': {
                    return $this->load_edit($args);
                }
            case 'quick': {
                    return $this->load_quick($args);
                }
        }

        return NULL;
    }

    private function load_default($args) {

        if (!isset($args['bounds']) || !isset($args['zoom'])) {
            error("'Default' action requires the 'bounds' and 'zoom' parameters.");
        }

        $labels = [
            'dynamic' => [],
            'static' => []
        ];

        // Expand by 300?
        $args['bounds']->expand($args['zoom'], 300);

        $this->add_poi($args, $labels);
        $this->add_pois($args, $labels);
        $this->add_types($args, $labels);
        $this->add_static($args, $labels);

        return [
            'labels' => $labels
        ];
    }

    private function load_click($args) {

        if (!isset($args['poiId'])) {
            error("'Click' action requires the 'poiId' parameter.");
        }

        $labels = [
            'dynamic' => [],
            'static' => []
        ];

        $args['poi'] = POIModel::load($args['poiId']);

        $this->compute_bounds($args, FALSE);
        $args['bounds']->expand($args['zoom'], 300);
        $labels['dynamic'][] = LabelModel::loadDynamic($args['poiId']);
        $this->add_static($args, $labels);

        $this->assign('poi', $args['poi']);

        return [
            'center' => $args['center'],
            'zoom' => $args['zoom'],
            'labels' => $labels,
            'card' => $this->load->view('templates/infobox', TRUE)
        ];
    }

    private function load_search($args) {

        if (!isset($args['poiId']) && !isset($args['types'])) {
            error("'Search' action requires the 'poiId' or 'types' parameter.");
        }

        $labels = [
            'dynamic' => [],
            'static' => []
        ];

        if (isset($args['poiId'])) {
            $args['poi'] = POIModel::load($args['poiId']);
            $this->compute_bounds($args);
        }

        $this->expand_bounds_to_types($args);

        $args['bounds']->expand($args['zoom'], 300);
        $this->add_poi($args, $labels);
        $this->add_types($args, $labels);
        $this->add_static($args, $labels);

        $card = NULL;

        if (isset($args['poi'])) {
            $this->assign('poi', $args['poi']);
            $card = $this->load->view('templates/infobox', TRUE);
        }

        return [
            'center' => $args['bounds']->get_center(),
            'zoom' => $args['zoom'],
            'labels' => $labels,
            'card' => $card
        ];
    }

    private function load_hash($args) {

        $labels = [
            'dynamic' => [],
            'static' => []
        ];

        $this->add_poi($args, $labels);

//        if (!isset($args['bounds'])) {
//            $this->compute_bounds($args);
//        } else {
//            $args['zoom'] = $args['bounds']->get_max_zoom($args['width'], $args['height']);
//            $args['bounds'] = LatLngBounds::from_dimensions($args['width'], $args['height'], $args['bounds']->get_center(), $args['zoom']);
//        }

        // The above has been replaced by this line NOT to compute
        // the bounds from given bounds when action is hash
        $this->compute_bounds($args);
//        if (!isset($args['zoom'])) {
//            $this->compute_bounds($args);
//        } else {
//           $zoom = $args['zoom'];
//           $this->compute_bounds($args);
//           $args['zoom'] = $zoom;
//           $args['bounds'] = LatLngBounds::from_dimensions($args['width'], $args['height'], $args['poi']->latLng(), $args['zoom']);
//        }

        $this->add_types($args, $labels);
        $this->add_static($args, $labels);

        $card = NULL;

        if (isset($args['poi'])) {
            $this->assign('poi', $args['poi']);
            $card = $this->load->view('templates/infobox', TRUE);
        }

        return [
            'labels' => $labels,
            'center' => $args['bounds']->get_center(),
            'zoom' => $args['zoom'],
            'card' => $card
        ];
    }

    private function load_edit($args) {

        if (!isset($args['poiId'])) {
            error("'Edit' action requires the 'poiId' parameter.");
        }

        $labels = [
            'dynamic' => [],
            'static' => []
        ];

        $args['poi'] = POIModel::load($args['poiId']);

        $this->compute_bounds($args, FALSE);
        $this->add_static($args, $labels);

        $this->assign('poi', $args['poi']);

        return [
            'center' => $args['center'],
            'zoom' => $args['zoom'],
            'labels' => $labels
        ];
    }

    private function load_quick($args) {

        if (!isset($args['poiId']) || !isset($args['poiIds'])) {
            error("'Quick' action requires the 'poiId' and 'poiIds' parameters.");
        }

        $labels = [
            'dynamic' => [],
            'static' => []
        ];
        
        if (count($args['poiIds']) === 1 && $args['poiIds'][0] === NULL) {
            $url = ltrim($args['poiUrls'][0], "/");
            $poi = POIModel::loadByUrl($url);
            $args['poiIds'] = [$poi->id()];
        }

        $args['poi'] = POIModel::load($args['poiId']);
        $args['pois'] = POIModel::loadByIds($args['poiIds']);

        $bounds = new LatLngBounds($args['poi']->latLng());
//        if ($args['poi']->has_border()) {
//            $bounds->extend_with_polygon($args['poi']->border());
//        }

        foreach ($args['pois'] AS $poi) {
            if ($poi->has_border()) {
                $bounds->extend_with_polygon($poi->border());
            } else {
                $bounds->extend($poi->latLng());
            }
        }

        $args['zoom'] = $bounds->get_max_zoom($args['width'], $args['height'], 30, 70, 30, 15);
        $args['bounds'] = LatLngBounds::from_dimensions($args['width'], $args['height'], $bounds->get_center(), $args['zoom']);

        $this->add_poi($args, $labels);
        $this->add_pois($args, $labels);
        $this->add_static($args, $labels);

        return [
            'center' => $args['bounds']->get_center(),
            'zoom' => $args['zoom'],
            'labels' => $labels
        ];
    }

    private function add_poi(&$args, &$labels) {
        if (isset($args['poiId']) || isset($args['poiUrl'])) {
            if (isset($args['poiUrl'])) {
                $args['poi'] = POIModel::loadByUrl($args['poiUrl']);
                $args['poiId'] = $args['poi']->id();
            } else {
                $args['poi'] = POIModel::load($args['poiId']);
            }
            if ($args['mode'] === 'default') {
                $labels['dynamic'][] = LabelModel::loadDynamic($args['poiId']);
            }
        }
    }

    private function add_pois(&$args, &$labels) {
        if (isset($args['poiIds'])) {
            $dynamic = LabelModel::loadDynamicByIds($args['poiIds']);
            $labels['dynamic'] = array_merge($labels['dynamic'], $dynamic);
        }
    }

    private function add_types(&$args, &$labels) {
        if (isset($args['types'])) {
            $excludeIds = [];
            isset($args['poiId']) && $excludeIds[] = $args['poiId'];
            if (isset($args['poiIds'])) {
                $excludeIds = array_merge($excludeIds, $args['poiIds']);
            }
            $dynamic = LabelModel::loadDynamicByBounds($args['bounds'], $args['types'], $excludeIds);
            $labels['dynamic'] = array_merge($labels['dynamic'], $dynamic);
        }
    }

    private function add_static(&$args, &$labels) {
        $excludeIds = [];
        $excludeTypes = [];
        isset($args['poiId']) && $excludeIds[] = $args['poiId'];
        if (isset($args['poiIds'])) {
            $excludeIds = array_merge($excludeIds, $args['poiIds']);
        }
        if (isset($args['types'])) {
            $excludeTypes = $args['types'];
        }
        $static = LabelModel::loadStaticByBounds2($args['bounds'], $args['zoom'], $excludeIds, $excludeTypes);
        $labels['static'] = array_merge($labels['static'], $static);
    }

    private function compute_bounds(&$args, $zoomIn = TRUE) {

        if ($args['poi']->has_border()) {
            $minBounds = LatLngBounds::from_polygon($args['poi']->border());
            $maxZoom = $minBounds->get_max_zoom($args['width'], $args['height']);
            if ($zoomIn) {
                $args['zoom'] = $maxZoom;
            } else {
                if (!isset($args['zoom'])) {
                    $args['zoom'] = MAP_DEFAULT_ZOOM;
                }
                $args['zoom'] = min($maxZoom, $args['zoom']);
            }
            $args['bounds'] = LatLngBounds::from_dimensions($args['width'], $args['height'], $minBounds->get_center(), $args['zoom']);
            $args['center'] = $args['bounds']->get_center();
        } else {
            if ($zoomIn || !isset($args['zoom'])) {
                $args['zoom'] = MAP_DEFAULT_ZOOM;
            }
            $args['bounds'] = LatLngBounds::from_dimensions($args['width'], $args['height'], $args['poi']->latLng(), $args['zoom']);
            $args['center'] = $args['poi']->latLng();
        }
    }

    private function expand_bounds_to_types(&$args) {
        if (isset($args['types'])) {
            $exceptId = isset($args['poiId']) ? $args['poiId'] : 0;
            while (!LabelModel::oneOfTypesWithinBounds($args['bounds'], $args['types'], $exceptId)) {
                $args['zoom'] --;
                $args['bounds'] = LatLngBounds::from_dimensions($args['width'], $args['height'], $args['bounds']->get_center(), $args['zoom']);
            }
        }
    }

    private function click($args) {

        if (!isset($args['poiId'])) {
            error("Click action requires 'poiId'");
        }

        $poi = POIModel::load($args['poiId']);
        $width = $args['width'];
        $height = $args['height'];
        $zoom = $args['zoom'];
        $bounds = NULL;

        if ($poi->has_border()) {
            $poiBounds = LatLngBounds::from_polygon($poi->border());
            $maxZoom = $poiBounds->get_max_zoom($width, $height);
            $zoom = min($zoom, $maxZoom);
            $bounds = LatLngBounds::from_dimensions($width, $height, $poiBounds->get_center(), $zoom);
        } else {
            $bounds = LatLngBounds::from_dimensions($width, $height, $poi->latLng(), $zoom);
        }

        return [
            'card' => '<html></html>',
            'center' => $bounds->get_center(),
            'zoom' => $zoom
        ];
    }

    /**
     * @AjaxCallable
     */
    public function load() {

        $this->load->library('geo/*');
        $this->load->library('MapManager');
        $this->load->model('POIModel');
        $this->load->model('LabelModel');
        $this->load->helper('html');

        $width = filter_input(INPUT_POST, 'width', FILTER_VALIDATE_INT);
        $height = filter_input(INPUT_POST, 'height', FILTER_VALIDATE_INT);
        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);
        $centerWKT = filter_input(INPUT_POST, 'center', FILTER_SANITIZE_STRING);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        $poiId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $poiUrl = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING);
        $poiIds = filter_input(INPUT_POST, 'ids', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        $types = filter_input(INPUT_POST, 'types', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $center = LatLng::fromWKT($centerWKT);
        $poiIds = $poiIds === NULL ? [] : $poiIds;
        $types = $types === NULL ? [] : $types;

        $mm = new MapManager($width, $height, $zoom);

        return [
            'labels' => [
                'static' => ['asd', 'psik'],
                'dynamic' => ['jelen']
            ]
        ];

//        if ($action == NULL) {
//            return $mm->load_default($center, $poiId, $poiUrl, $poiIds, $types);
//        }
//        
//        if ($action == 'click') {
//            return $mm->load_click($poiId, $poiUrl);
//        }
//        
//        if ($action == 'search') {
//            return $mm->load_search($center, $poiId, $poiUrl, $types);
//        }
//        
//        if ($action == 'params') {
//            return $mm->load_params($center, $poiId, $poiUrl, $poiIds, $types);
//        }
    }

}
