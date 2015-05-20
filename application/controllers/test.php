<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        require_library('geo/*');
        require_library('geo/*');
    }

    function icons() {
        $this->load->view('icons');
    }
    
    function nearby() {
        
        require_library('geo/*');
        require_model('POIModel');
        $this->load->helper('geo');
        
        $poiId = 21;
        
        $poi = POIModel::load($poiId);
        $bounds = NULL;
        
        if ($poi->has_border()) {
            $bounds = LatLngBounds::from_polygon($poi->border());
            $bounds->grow(1);
        } else {
            $bounds = new LatLngBounds($poi->latLng());
            $bounds->grow(1);
            echo $bounds->get_north_east()->lat() . "," . $bounds->get_north_east()->lng() . "\n";
            echo $bounds->get_south_west()->lat() . "," . $bounds->get_south_west()->lng() . "\n";
        }
        
        // Load everything within the bounds
        
        POIModel::load_pois_near($poiId);
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
