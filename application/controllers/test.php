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
