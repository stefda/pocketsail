<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function markup() {
        $text = "**Subheader**\nTextText\n\n**Dalsi Subheader**Text Text text\nThis link [Google|http://www.google.com] is working!";
        $headedText = preg_replace('/\*\*([^*]*)\*\*\n?/', "<h2>\\1</h2>", $text);
        echo preg_replace('/\[([^|]*)\|([^\]]*)\]/', "<a href=\"\\2\">\\1</a>", $headedText);
    }

    function index() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $poiId = filter_input(INPUT_GET, 'poiId', FILTER_VALIDATE_INT);
        $lat = filter_input(INPUT_GET, 'lat', FILTER_VALIDATE_FLOAT);
        $lng = filter_input(INPUT_GET, 'lng', FILTER_VALIDATE_FLOAT);
        $cat = filter_input(INPUT_GET, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_GET, 'sub', FILTER_SANITIZE_STRING);

        $poiObject = new stdClass();
        $attrsObject = new stdClass();

        if ($poiId !== NULL) {
            $poi = POIModel::load($poiId);
            $poiObject->id = $poi->id();
            $poiObject->name = $poi->name();
            $poiObject->cat = $poi->cat();
            $poiObject->sub = $poi->sub();
            $poiObject->latLng = $poi->latLng();
            $poiObject->border = $poi->border();
            $attrsObject = $poi->attributes();
        } else {
            $poiObject->id = 0;
            $poiObject->name = '';
            $poiObject->cat = $cat;
            $poiObject->sub = $sub;
            $poiObject->latLng = new LatLng($lat, $lng);
            $poiObject->border = null;
        }

        $this->assign('poi', $poiObject);
        $this->assign('attrs', $attrsObject);
        $this->load->view('templates/edit');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function post() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
        $sub = filter_input(INPUT_POST, 'sub', FILTER_SANITIZE_STRING);
        $latLngWKT = filter_input(INPUT_POST, 'latLng', FILTER_SANITIZE_STRING);
        $borderWKT = filter_input(INPUT_POST, 'border', FILTER_SANITIZE_STRING);
        $attrs = filter_input(INPUT_POST, 'attrs', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $latLng = LatLng::fromWKT($latLngWKT);
        $border = Polygon::fromWKT($borderWKT);

        POIModel::update($id, 1, 1, $name, $name, $cat, $sub, $latLng, $border, $attrs);
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
