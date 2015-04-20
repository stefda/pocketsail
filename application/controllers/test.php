<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->view('addPoi');
    }
    
    function leaflet() {
        $this->load->view('leaflet');
    }
    
    function geo() {
        $this->load->view('geo');
    }

    function add() {
        $data = $_POST['data'];
        $mongo = get_mongo();
        $mongo->insert_inc('poi', json_decode($data, TRUE));
//        $mongo->find('poi', []);
//        while ($doc = $mongo->next()) {
//            print_R($doc['_id']);
//        }
    }

    function fill() {

        $this->load->library('geo/Point');

        $mongo = get_mongo();

        $doc = [
            'name' => "Palmizana Marina",
            'url' => "hvar_marina",
            'latLng' => (new Point([16.4, 43.17]))->toGeoJson(),
            'description' => "Popis mariny netreba. Busy.",
            'berthing' => (object) [
                'assistance' => (object) [
                    'value' => 'yes',
                    'details' => 'Guy with a blue hat and a torch at nitgh.'
                ],
                'type' => (object) [
                    'value' => ['alongside', 'stem-to'],
                    'details' => 'No comment'
                ],
                'sea_berths' => (object) [
                    'total' => 23,
                    'visitors' => 0,
                    'details' => ''
                ]
            ]
        ];

        $mongo->insert_inc('poi', $doc);
        
//        $mongo->find('poi', []);
//        while ($doc = $mongo->next()) {
//            print_r($doc);
//        }
    }

}
