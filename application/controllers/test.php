<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function curl() {
        echo $_SERVER['REQUEST_METHOD'] . "\n";
    }
    
    function ceil() {
        
        var_dump(ceil(85 - 10));
    }

    function sol() {

        function solution($A) {

            $N = count($A);
            $M = count($A[0]);

            $P = [0];
            $Q = [0];
            $S = [$A[0][0]];

            $max = $A[0][0];

            while (count($P) > 0) {

                $p = array_shift($P);
                $q = array_shift($Q);
                $s = array_shift($S);

                if ($p + 1 != $N) {
                    $P[] = $p + 1;
                    $Q[] = $q;
                    $S[] = $s + $A[$p + 1][$q];                    
                    if (end($S) > $max) {
                        $max = end($S);
                    }
                }

                if ($q + 1 != $M) {
                    $P[] = $p;
                    $Q[] = $q + 1;
                    $S[] = $s + $A[$p][$q + 1];
                    if (end($S) > $max) {
                        $max = end($S);
                    }
                }
            }
            
            return $max;
        }
        
        $A = [
            [2, 0, 5, 1],
            [0, 4, 1, 3],
            [0, 2, 0, 0]
        ];

        echo solution($A);
    }

    function index() {
        $this->load->view("test");
    }

    function icons() {
        $this->load->view('icons');
    }

    function cat() {
        require_model('POITypeModel');
        print_R(POITypeModel::cat_name_map());
    }

    function nearby() {

        require_library('geo/*');
        require_model('POIModel');
        $this->load->helper('geo');

        $poiId = 22;
        $mysql = get_mysql();

        $poi = POIModel::load($poiId);
        $nearbys = [];
        $bounds = NULL;

        if ($poi->has_border()) {
            $bounds = LatLngBounds::from_polygon($poi->border());
            $bounds->grow(1);
        } else {
            $bounds = new LatLngBounds($poi->latLng());
            $bounds->grow(1);
        }

        $ne = $bounds->get_north_east();
        $sw = $bounds->get_south_west();

        $nearbyIds = [$poiId];
        $hasGasstation = FALSE;

        $rows = POIModel::load_within_bounds($bounds);
        $rows = array_merge($rows, POIModel::load_neabys($poiId));

        foreach ($rows AS $row) {
            if (!isset($nearbys[$row['cat']])) {
                $nearbys[$row['cat']] = [];
            }
            $row['distance'] = haversine($poi->lat(), $poi->lng(), $row['lat'], $row['lng']);
            if (!in_array($row['id'], $nearbyIds)) {
                $nearbys[$row['cat']][] = $row;
                $nearbyIds[] = $row['id'];
                if ($row['sub'] === 'gasstation') {
                    $hasGasstation = TRUE;
                }
            }
        }

        if (!$hasGasstation) {
            $bounds = new LatLngBounds($poi->latLng());
            $bounds->grow(32);
            $gasstations = POIModel::load_sub_within_bounds($bounds, 'gasstation');
            foreach ($gasstations AS &$gasstation) {
                $gasstation['distance'] = haversine($poi->lat(), $poi->lng(), $gasstation['lat'], $gasstation['lng']);
            }
            if (!isset($nearbys['refuelling'])) {
                $nearbys['refuelling'] = [];
            }
            $nearbys['refuelling'] = $gasstations;
        }

        foreach ($nearbys AS &$nearby) {
            aasort($nearby, 'distance');
        }

        print_r($nearbys);
    }

    function fulltext() {

        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $term = strtolower(trim($term));

        $this->load->library('solr/SolrService');
        SolrService::$SERVLET = 'spell';
        $solr = SolrService::get_instance();

        $res = $solr->fulltext($term);

        $this->assign('term', $term);
        $this->assign('numFound', $res->num_found());
        $this->assign('numDocs', $res->num_docs());
        $this->assign('docs', $res->docs());
        $this->assign('highlights', $res->get_highlights());
        $this->assign('spellingError', !$res->is_spelled_correctly());
        $this->assign('suggestion', $res->get_collation());

        $this->load->view('fulltext');
    }

}
