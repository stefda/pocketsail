<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $poi = POIModel::load(13);
        $attrs = $poi->attributes();
        $this->assign('poi', $poi);
        $this->assign('attrs', $attrs);
        $this->load->view('templates/edit');
    }
    
    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function post() {
        print_r($_POST);
    }
    
    function select() {
        $this->load->view('select');
    }

    function main() {
        $this->load->view('main');
    }

    function bounds() {

        $types = [];
        echo "('" . implode("','", $types) . "')";
        exit();

        $pois = [];
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT `id`, `name`, AsText(`border`) AS `borderWKT` FROM `poi` WHERE `border` IS NOT NULL");
        while ($o = $mysql->fetch_object($r)) {
            $pois[] = [
                'id' => $o->id,
                'name' => $o->name,
                'borderWKT' => $o->borderWKT
            ];
        }
        $this->assign('pois', $pois);
        $this->load->view('testBounds');
    }

    function fulltext() {
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $term = strtolower(trim($term));
        $this->load->library('solr/SolrService');
        $solr = SolrService::get_instance();
        $res = $solr->query("fulltext:($term)", 10);
        $this->assign('term', $term);
        $this->assign('numFound', $res->num_found());
        $this->assign('numDocs', $res->num_docs());
        $this->assign('docs', $res->docs());
        $this->load->view('fulltext');
    }

    function suggest() {
        $this->load->view('search');
    }

    function search() {

        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

        $this->load->library('solr/SolrService');
        $solr = SolrService::get_instance();

//        $keywords = [
//            "berthing" => ["cat" => ["berthing"]],
//            "anchoring" => ["cat" => ["anchoring"]],
//            "marina" => ["sub" => ["marina"]],
//            "marinas" => ["sub" => ["marina"]],
//            "anchoring" => ["cat" => ["anchoring"]],
//            "anchorage" => ["sub" => ["anchorage"]],
//            "anchorages" => ["sub" => ["anchorage"]],
//            "buoy" => ["sub" => ["buoys"]],
//            "buoys" => ["sub" => ["buoys"]],
//            "mooring buoy" => ["sub" => ["buoys"]],
//            "mooring buoys" => ["sub" => ["buoys"]],
//            "restaurant" => ["sub" => ["restaurant"]],
//            "restaurants" => ["sub" => ["restaurant"]],
//            "bar" => ["sub" => ["bar"]],
//            "bars" => ["sub" => ["bar"]],
//            "restaurants and bars" => ["sub" => ["restaurant", "bar"]],
//            "gas station" => ["sub" => ["gasstation"]],
//            "gas stations" => ["sub" => ["gasstation"]],
//            "shop" => ["sub" => ["supermarket"]],
//            "shops" => ["sub" => ["supermarket"]],
//            "supermarket" => ["sub" => ["supermarket"]],
//            "supermarkets" => ["sub" => ["supermarket"]],
//            "pharmacy" => ["sub" => ["pharmacy"]],
//            "pharmacies" => ["sub" => ["pharmacy"]],
//        ];

        $keywords = [
            'berthing' => ["berthing"],
            'anchoring' => ["anchoring"],
            'marina' => ["marina"],
            'marinas' => ["marina"],
            'restaurant' => ["restaurant"],
            'restaurants' => ["restaurant"],
            'bar' => ["bar"],
            'bars' => ["bar"],
            'restaurants and bars' => ["restaurant", "bar"],
            'gas station' => ["gasstation"],
            'gas stations' => ["gasstation"],
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
