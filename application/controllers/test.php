<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function pages() {
        $this->load->view('pages');
    }
    
    function code() {

        define('ENC_CYP', 'aslfoiwupqoiefdk');

        function encid($id) {
            $code = '';
            $id = str_pad($id, 8, '0', STR_PAD_LEFT);
            for ($i = 0; $i < strlen($id); $i++) {
                $code .= substr($id, $i, 1);
                $cpos = rand(0, strlen(ENC_CYP) - 1);
                $char = substr(ENC_CYP, $cpos, 1);
                $code .= $char;
            }
            return base64_encode($code);
        }

        function decid($cyp) {
            $decoded = base64_decode($cyp);
            $decId = '';
            for ($i = 0; $i < strlen($decoded); $i++) {
                $decId .= substr($decoded, $i * 2, 1);
            }
            $decId = ltrim($decId, '0');
            return intval($decId);
        }

        echo encid(23443);
        
//        $start = microtime(TRUE);
//        for ($i = 1; $i < 10000; $i++) {
//            $after = decid(encid($i));
//            if ($i !== $after) {
//                echo "error";
//                exit();
//            }
//        }
//        echo microtime(TRUE) - $start;
        exit();
    }

    function markup() {

        $start = microtime(TRUE);

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $poi = POIModel::load(95);

        // Load nears
        if ($poi->border() === NULL) {
            $r = sqrt(2 * pow(9, 2));
            $sw = Geo::proximity($poi->latLng(), $r, 215);
            $ne = Geo::proximity($poi->latLng(), $r, 45);
            $vb = new ViewBounds($sw, $ne);
            $poiBorder = $vb->toPolygon();
        } else {
            $poiBorder = $poi->border();
        }

        $near = POIModel::loadByBorder($poiBorder, ['restaurant', 'supermarket', 'gasstation', 'anchorage', 'buoys'], 198);
        $nearSorted = [];
        $nearIds = [];

        // Separate into subcategories
        foreach ($near AS $poiTo) {
            $sub = $poiTo->sub();
            if (!array_key_exists($sub, $nearSorted)) {
                $nearSorted[$sub] = [];
                $nearIds[$sub] = [];
            }
            $nearSorted[$sub][] = [
                'poi' => $poiTo->toObject(),
                'dist' => Geo::haversine($poi->latLng(), $poiTo->latLng())
            ];
            $nearIds[] = $poiTo->id();
        }

        print_r($nearIds);
        exit();

        // Sort each subcategory by disance
        foreach ($nearSorted AS &$near) {
            aasort($near, 'dist');
        }

        echo microtime(TRUE) - $start;

        print_r($nearSorted);
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
            $poiObject = $poi->toObject();
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

    function view() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $poiId = filter_input(INPUT_GET, 'poiId', FILTER_VALIDATE_INT);

        $poiObject = new stdClass();
        $attrsObject = new stdClass();

        $poi = POIModel::load($poiId);
        $poiObject->id = $poi->id();
        $poiObject->name = $poi->name();
        $poiObject->cat = $poi->cat();
        $poiObject->sub = $poi->sub();
        $poiObject->latLng = $poi->latLng();
        $poiObject->border = $poi->border();
        $attrsObject = $poi->attributes();

        $r = sqrt(2 * pow(9, 2));
        $sw = Geo::proximity($poi->latLng(), $r, 215);
        $ne = Geo::proximity($poi->latLng(), $r, 45);
        $vb = new ViewBounds($sw, $ne);
        $poiBorder = $vb->toPolygon();

        $near = POIModel::loadByBorder($poiBorder, ['restaurant', 'supermarket', 'gasstation', 'anchorage', 'buoys'], 198);
        $nearSorted = [];
        $nearSortedIds = [];

        // Separate into subcategories
        foreach ($near AS $poiTo) {
            $sub = $poiTo->sub();
            if (!array_key_exists($sub, $nearSorted)) {
                $nearSorted[$sub] = [];
                $nearSortedIds[$sub] = [];
            }
            $nearSorted[$sub][] = [
                'poi' => $poiTo->toObject(),
                'dist' => Geo::haversine($poi->latLng(), $poiTo->latLng())
            ];
            $nearSortedIds[$sub][] = $poiTo->id();
        }

        // Sort each subcategory by disance
        foreach ($nearSorted AS &$near) {
            aasort($near, 'dist');
        }

        $this->assign('poi', $poiObject);
        $this->assign('attrs', $attrsObject);
        $this->assign('near', (object) $nearSorted);
        $this->assign('nearIds', $nearSortedIds);
        $this->load->view('templates/view');
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
