<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Geo');
        $this->load->library('geo/Point');
        $this->load->library('geo/LatLng');
        $this->load->library('geo/Polygon');
        $this->load->library('geo/Bounds');
        $this->load->model('POIModel');
        $this->load->model('POIIndexModel');
        $this->load->model('POICategoryModel');
        $this->load->helper('ps');
    }

    function four() {
        $res = file_get_contents("https://api.foursquare.com/v2/venues/search?client_id=QZ1G1UWHOXTGA5C0BCMWMMC1WXB51Q10SBUI0QSBFPU2NVCU&client_secret=CEWX24LZ3LKNVN0EX01U13JI1IJZ2I2JDV0D2R4ZQS52YZPR&v=20130815&ll=43.871168,15.319766&query=restaurant");
        $data = json_decode($res);
        $venues = $data->response->venues;
        for ($i = 0; $i < count($venues); $i++) {
            //print_r($venues[$i]);
            echo $venues[$i]->name . "\n";
        }
    }
    
    function test() {
        $this->load->view('search');
//        $this->load->library('solr/SolrService');
//        $solr = SolrService::get_instance();
//        $res = $solr->query("fulltext:(marina sukosan)", 3);
//        echo ($res->num_docs());
//        $docs = $res->docs();
    }

    function search() {

        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        
        $this->load->library('solr/SolrService');
        $solr = SolrService::get_instance();
        
        $keywords = [
            "berthing" => ["cat" => ["berthing"]],
            "anchoring" => ["cat" => ["anchoring"]],
            "marina" => ["sub" => ["marina"]],
            "marinas" => ["sub" => ["marina"]],
            "anchoring" => ["cat" => ["anchoring"]],
            "anchorage" => ["sub" => ["anchorage"]],
            "anchorages" => ["sub" => ["anchorage"]],
            "buoy" => ["sub" => ["buoys"]],
            "buoys" => ["sub" => ["buoys"]],
            "mooring buoy" => ["sub" => ["buoys"]],
            "mooring buoys" => ["sub" => ["buoys"]],
            "restaurant" => ["sub" => ["restaurant"]],
            "restaurants" => ["sub" => ["restaurant"]],
            "bar" => ["sub" => ["bar"]],
            "bars" => ["sub" => ["bar"]],
            "restaurants and bars" => ["sub" => ["restaurant", "bar"]],
            "gas station" => ["sub" => ["gasstation"]],
            "gas stations" => ["sub" => ["gasstation"]],
            "shop" => ["sub" => ["supermarket"]],
            "shops" => ["sub" => ["supermarket"]],
            "supermarket" => ["sub" => ["supermarket"]],
            "supermarkets" => ["sub" => ["supermarket"]],
            "pharmacy" => ["sub" => ["pharmacy"]],
            "pharmacies" => ["sub" => ["pharmacy"]],
        ];

        $this->load->library('Search');
        $s = new Search($solr, $keywords);
        $items = $s->do_search($term);
        
        echo json_encode($items);
    }

    function labeller() {
        $this->load->view("test/labeller");
    }

    function mapbox() {
        $this->load->view("mapbox");
    }

    function map() {
        $this->load->view('map');
    }

    function get_labels($n, $e, $s, $w, $zoom) {

        $mysql = CL_MySQL::get_instance();

        $bbox = Geo::create_bbox($n, $e, $s, $w);
        $bboxWKT = $bbox->to_WKT();
        $r = $mysql->query("SELECT *, AsText(`latLng`) AS `latLngWKT` FROM `poi_label_zoom` WHERE `zoom` = $zoom AND MBRContains(GeomFromText('$bboxWKT'), `latLng`)");
        $json = [];
        while ($o = $mysql->fetch_object($r)) {
            $latLng = LatLng::from_WKT($o->latLngWKT);
            $o->lat = $latLng->lat;
            $o->lng = $latLng->lng;
            $json[] = $o;
        }
        echo json_encode($json);
    }

    function design() {
        $this->load->view('design');
    }

    function position() {
        $this->load->view('position');
    }

}
