<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        echo 'Test::index';
    }

    function mysql() {

        $conn = new PDO("mysql:host=localhost;dbname=pocketsail;charset=utf8", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $conn->prepare("INSERT INTO test (id, value) VALUES (:id, :value)");
        $stmt->execute([
            ':id' => 34,
            ':value' => 'asd'
        ]);
        
        print_r($conn->errorInfo());
        
//        $mysql = get_mysql();

//        $mysql->update('photo_info', [
//            'description' => 'Jelen'
//                ], [
//            'id' => 1
//        ]);

//        $mysql->query("INSERT INTO `photo_info` (`poiId`, `main`, `description`) VALUES (1, 0, '')");
//        $stmt = $mysql->prepare("INSERT INTO `photo_info` (`poiId`, `main`, `description`) VALUES (?, ?, ?)");
//        $stmt->execute([
//            'poiId' => 12,
//            'main' => false,
//            'description' => ''
//        ]);
        
//        $mysql->insert('photo_info', [
//            'poiId' => 34,
//            'main' => true,
//            'description' => 'Jelen'
//        ]);
//        
//        var_dump($res);
//        $mysql->execute("INSERT INTO photo_info (poiId, main, description) VALUES (:poiId, :main, :description)", [
//            ':poiId' => 123,
//            ':main' => FALSE,
//            ':description' => 'Deskripesxcsd.'
//        ]);
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=GET
     */
    function get_next_poi($id) {

        require_library('geojson/*');

        $mysql = get_mysql();
        $rows = $mysql->fetch_all("SELECT *, AsText(`border`) AS `border` FROM `poi` WHERE `id` > ? AND `border` IS NOT NULL LIMIT 1", [$id]);

        if (count($rows) > 0) {
            $row = $rows[0];
            $border = Polygon::from_wkt($row['border']);
            return [
                'id' => $row['id'],
                'name' => $row['name'],
                'latLng' => new LatLng($row['lat'], $row['lng']),
                'border' => $border,
                'bounds' => LatLngBounds::from_polygon($border)
            ];
        }
    }

    function back() {
        require_library('geo/*');
        $bounds = new Bounds(new LatLng(0, 0), new LatLng(1, 1));
        echo $bounds->getMaxZoom(512, 512);
    }

    function geo() {
        $this->assign('id', isset($_GET['id']) ? $_GET['id'] : 0);
        $this->load->view('geo');
    }

    function geojson() {
        require_library('geojson/*');
        $poly = new Polygon([[[1, 1], [1, 2], [2, 2], [2, 1], [1, 1]]]);
        $bounds = LatLngBounds::from_polygon($poly);
        echo $bounds;
    }

    /**
     * @AjaxCallable=TRUE
     */
    function apply() {
        require_library('geojson/*');
        $gjBounds = $_POST['bounds'];
        $width = $_POST['width'];
        $height = $_POST['height'];
        $bounds = LatLngBounds::from_geo_json($gjBounds);
        $zoom = $bounds->get_max_zoom($width, $height);
        $center = $bounds->get_center();
        return [
            'bounds' => $bounds,
            'zoom' => $zoom,
            'center' => $center
        ];
    }

    function r() {
        error("Mame problem");
        return [1, 2, 3];
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
