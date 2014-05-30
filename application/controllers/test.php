<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
//        $this->load->library('Geo');
//        $this->load->library('geo/Point');
//        $this->load->library('geo/LatLng');
//        $this->load->library('geo/Polygon');
//        $this->load->library('geo/Bounds');
//        $this->load->model('POIModel');
//        $this->load->model('POIIndexModel');
//        $this->load->model('POICategoryModel');
//        $this->load->helper('ps');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function compute() {
        $this->load->library('geo/*');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $vBoundsWKT = filter_input(INPUT_POST, 'vBounds', FILTER_SANITIZE_STRING);
        $zoom = filter_input(INPUT_POST, 'zoom', FILTER_VALIDATE_INT);

        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT `id`, `name`, AsText(`boundary`) AS `boundaryWKT` FROM `poi` WHERE `id` = $id");
        $o = $mysql->fetch_object($r);

        $vBounds = ViewBounds::fromWKT($vBoundsWKT);
        $boundary = Polygon::fromWKT($o->boundaryWKT);
        $poiBounds = ViewBounds::fromPolygon($boundary);

        $vBounds->setCenter($poiBounds->getCenter());
        $vBounds->fitBounds($poiBounds, $zoom);

        //$bounds = ViewBounds::fromWKT($boundsWKT);
        return [
            'center' => $poiBounds->getCenter(),
            'zoom' => $zoom
        ];
    }

    function index() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->model('LabelModel');
        
        print_r(LabelModel::load(34));
        
        //echo POIModel::add(1, 1, 1, 'David', 'Vino', 'berthing', 'marina', new LatLng(44, 16), new Polygon(), []);
        //POIModel::update(454, 100, 100, 'David', 'David', 'mercat', 'tacrem', new LatLng(44, 44), new Polygon(), []);
//        print_r(POIModel::load(1)->info());

//        $this->load->library('geo/*');
//        $this->load->library('mysql/*');
//        $this->load->model('LabelModel');
//        $this->load->model('POIModel');
//
//        $latLng = new LatLng(44, 17);
//        $border = Polygon::fromWKT('POLYGON((17 44,17 43,13 43,13 44,17 44))');
//
//        $mysql = CL_MySQLi::get_instance();
//
//        $q = insert($mysql)
//                ->into('poi')
//                ->value('id', NULL)
//                ->value('nearId', NULL)
//                ->value('countryId', NULL)
//                ->value('userId', 34)
//                ->value('name', 'Vino Uherkse Hradiste')
//                ->value('label', 'Vinogradiste')
//                ->value('cat', 'berthing')
//                ->value('sub', 'marina')
//                ->value('latLng', $latLng->toWKT())->op(GEOM_FROM_TEXT)
//                ->value('border', $border->toWKT())->op(GEOM_FROM_TEXT)
//                ->value('features', json_encode([]));
//        
//        $q = "SELECT * FROM `poi`";
//        $r = $mysql->query($q);
//        
//        var_dump($r->numRows());
//        
//        $mysql->close();
        //POIModel::add(1, 1, 1, 'Dalsi', 'Dalsi Vlk', 'marina', 'marina', new LatLng(44, 17), new Polygon(), []);
//        $bounds = ViewBounds::fromWKT('BOUNDS(13 43,17 44)')->toBounds();
//        $zoom = 10;
//        //$labels = LabelModel::loadStaticByBounds($bounds, $zoom, 12, ['marina', 'gasstation'], FALSE);
//        $labels = LabelModel::loadDynamicByBounds($bounds, ['marina', 'gasstation', 'cove', 'supermarket']);
//        
//        foreach ($labels AS $label) {
//            echo json_encode($label);
//            br();
//        }
    }

    function bounds() {
        $pois = [];
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT `id`, `name`, AsText(`boundary`) AS `boundaryWKT` FROM `poi` WHERE `boundary` IS NOT NULL");
        while ($o = $mysql->fetch_object($r)) {
            $pois[] = [
                'id' => $o->id,
                'name' => $o->name,
                'boundaryWKT' => $o->boundaryWKT
            ];
        }
        $this->assign('pois', $pois);
        $this->load->view('testBounds');
    }

    function mysql() {

        $this->load->library('mysql/*');
        $this->load->library('geo/*');

        $mysql = MySQLConnection::get_instance();
        $stmt = $mysql->prepare_statement("SELECT *, AsText(`border`) AS `borderWKT` FROM `test` WHERE `id` > ?");
        $stmt->bind_param([
            1
        ]);
//        $stmt->bind_param([
//            NULL,
//            "David",
//            23.345,
//            Polygon::fromWKT("POLYGON((23 34,23 44,34 65,23 34))")->toWKT()
//        ]);
        $stmt->execute();

        while (($o = $stmt->fetch()) !== FALSE) {
            $border = Polygon::fromWKT($o->borderWKT);
            $viewBounds = ViewBounds::fromPolygon($border);
            $viewBounds->zoomIn(0);
            echo $viewBounds->toWKT();
            br();
        }

        $stmt->close();
        $mysql->close();
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
