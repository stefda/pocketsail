<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
        echo "Test::index";
    }
    
    function cool() {
        
        $this->load->model('POIModel');
        $this->load->model('LabelModel');
        $this->load->library('MapManager');
        $this->load->library('geo/*');
        
        $width = 900;
        $height = 600;
        $zoom = 14;
        $latLng = new LatLng(43.3266, 16.4465);
        $id = 0;
        $url = 'marina-tankerkomerc-zadar';
        $types = ['anchorage'];
        
        $mm = new MapManager();
        
        //print_r($mm->click(900, 600, 14, 15));
        print_r($mm->search($width, $height, $zoom, $latLng, $id, $url, $types));
    }
    
    function geo2() {
        $this->load->view("geo2");
    }
    
    function geo() {
        $this->load->library('geo/*');
//        $bounds = new Bounds(new LatLng(0, 0), new LatLng(10, 10));
        $bounds = (new ViewBounds(new LatLng(0, 0), new LatLng(10, 10)))->toBounds();
        echo $bounds->getMaxZoom(1, 1);
    }
    
    function hash() {
        $this->load->library('geo/*');
        $this->load->model('POIModel');
        $this->load->view('hash');
        $poi = POIModel::loadByUrl('luka-rogac-harbour-berths');
        print_r($poi);
    }
    
    function html() {
        $this->load->helper('html');
        echo html("**Heading**\nasd\n[Ciovo Les|/google.com]");
    }

    function save_image() {

        $img = $_FILES['img'];

        $imgTempPath = $img['tmp_name'];
        $imgTempName = $img['name'];
        $imgTempType = $img['type'];

        $fp = fopen($imgTempPath, 'r');
        $string = fread($fp, filesize($imgTempPath));
        fclose($fp);

        //$imSize = getimagesize($imgTempPath);
//        $imWidth = $imSize[0];
//        $imHeight = $imSize[1];

        $width = 970;
        $height = 720;
        $ratio = $width / $height;

        $im = imagecreatefromstring($string);
        $imWidth = imagesx($im);
        $imHeight = imagesy($im);
        $imRatio = $imWidth / $imHeight;

        if ($imWidth < $width && $imHeight < $height) {
            $width = $imWidth;
            $height = $imHeight;
        } else {
            if ($imRatio > $ratio) {
                $rr = $width / $imWidth;
                $height = $imHeight * $rr;
            } else if ($imRatio < $ratio) {
                $rr = $height / $imHeight;
                $width = $imWidth * $rr;
            }
        }

        $newIm = imagecreatetruecolor($width, $height);
        imagecopyresampled($newIm, $im, 0, 0, 0, 0, $width, $height, $imWidth, $imHeight);

        $this->load->model('ImageModel');

        $imId = ImageModel::add(1, 1, 'Credits', 'Description');

        $fldName = floor($imId / 100);
        $newImName = ($imId % 100) . '.jpeg';
        $path = BASEPATH . 'db/images/full/' . $fldName . '/';

        // Create dir if not exists
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        imagejpeg($newIm, $path . $newImName);
        imagedestroy($newIm);
        imagedestroy($im);
        exit();

        header('Content-Type: image/jpeg');
        imagejpeg($newIm);
        imagedestroy($newIm);
        imagedestroy($im);
        exit();

//        header('Content-Type: image/png');
//        imagepng($im);
//        imagedestroy($im);
//        exit();
//        header('Content-Type: ' . $imgTempType);
//        echo $string;
//        exit();
//        $credits = filter_input(INPUT_POST, 'credits', FILTER_SANITIZE_STRING);
//        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    }

    function fulltext() {
        
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $term = strtolower(trim($term));
        
        $this->load->library('solr/SolrService');
        SolrService::$SERVLET = 'spell';
        $solr = SolrService::get_instance();
        
        $res = $solr->fulltext($term);
        
//        print_r($res);
//        exit();
        
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
