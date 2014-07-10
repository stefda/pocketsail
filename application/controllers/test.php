<?php

class Test extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {

        $coords = [[43.8644045, 15.3425008], [43.8642458, 15.3424979], [43.8641542, 15.3426165], [43.8641440, 15.3427266], [43.8642112, 15.3428451], [43.8643394, 15.3428931], [43.8645022, 15.3428874], [43.8645937, 15.3427745], [43.8645978, 15.3426673], [43.8645144, 15.3425403], [43.8644045, 15.3425008]];

        $this->load->library('geo/*');

        $p = Geo::latlng2meters(new LatLng(43.86435, 15.34273));
        
        echo $p->x() . "," . $p->y();

//        foreach ($coords AS $coord) {
//            $latLng = new LatLng($coord[0], $coord[1]);
//            $met = Geo::latlng2meters($latLng);
//            echo $met->x() . " " . $met->y() . "<br />";
//        }
    }

    function tpl() {

        $attrs = [
            "description" => [
                "val" => "Desckriptorwe."
            ],
            "approach" => [
                "val" => "Approach lightly.",
                "drying" => [
                    "val" => "yes",
                    "details" => [
                        "val" => ""
                    ]
                ]
            ]
        ];

        $attrs = json_decode(json_encode($attrs));

        $this->load->helper('tpl');
        $this->assign('attrs', $attrs);
        $this->load->view('tpl/add');
    }

    function attr() {

        $attrs = [
            "approach" => [
                "val" => "Approach lightly.",
                "details" => [
                    "val" => "Detaily"
                ]
            ],
            "contact" => [
                "type" => [
                    "tel",
                    "fax",
                    "http"
                ],
                "val" => [
                    213,
                    "02087888818",
                    "http://www.ps.com"
                ]
            ],
            "berthing" => [
                "type" => [
                    "val" => ["sternto", "bowto"],
                    "details" => [
                        "val" => "Berthing type details."
                    ]
                ],
                "assistance" => [
                    "val" => "yes",
                    "details" => [
                        "val" => ""
                    ]
                ]
            ]
        ];

        $attrs = json_decode(json_encode($attrs));

        global $attr;
        $attr = $attrs;

        function a() {
            global $attr;
            $args = func_get_args();
            $temp = &$attr;
            foreach ($args AS $arg) {
                if (is_object($temp) && property_exists($temp, $arg)) {
                    $temp = &$temp->{$arg};
                } else {
                    return NULL;
                }
            }
            if (is_object($temp) && property_exists($temp, 'val')) {
                return $temp->val;
            } elseif (is_array($temp)) {
                return $temp;
            } else {
                return NULL;
            }
        }

        function v($attr) {
            $args = func_get_args();
            array_shift($args);
            $temp = &$attr;
            foreach ($args AS $arg) {
                if (is_object($temp) && property_exists($temp, $arg)) {
                    $temp = &$temp->{$arg};
                } else {
                    return NULL;
                }
            }
            if (is_object($temp) && property_exists($temp, 'val')) {
                return $temp->val;
            } elseif (is_array($temp)) {
                return $temp;
            } else {
                return NULL;
            }
        }

        function attr_edit_tpl($attrName, $attribute) {
            global $attr;
            $attr = $attribute;
            echo CL_Loader::get_instance()->view('templates/edit/' . $attrName, FALSE);
        }

        function attr_view_tpl($attrName, $attribute) {
            global $attr;
            $attr = $attribute;
            return CL_Loader::get_instance()->view('templates/view/' . $attrName, FALSE);
        }

        echo attr_edit_tpl('test', @$attrs->approach);

        //attrs[contact][type][]
        //attrs[contact][val][]
    }

    function image() {
        $this->load->model('ImageModel');
        $path = ImageModel::id2path(9);
        $this->assign('path', $path);
        $this->load->view('image');
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

    function icons() {
        $this->load->view('icons');
    }

    function mobile() {
        $this->load->view('mobile');
    }

    function area() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');
    }

    function poi() {

//        $o = [
//            "int" => 4,
//            "str" => "asd"
//        ];
//        
//        $o = json_decode(json_encode($o));
//        
//        var_dump($o->int);
//        $this->load->model('POIModel');
//        $this->load->model('LabelModel');
//        $this->load->library('geo/*');
//        
//        $labels = LabelModel::loadNew(new ViewBounds(new LatLng(40, 13), new LatLng(48, 19)), 1);        
//        print_r($labels);
    }

    function menu() {
        $this->load->view('menu');
    }

    function edit() {

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

    function pages() {
        $this->load->view('pages');
    }

    function suggest() {
        $this->load->view('suggest');
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

    /**
     * @AjaxCallable=TRUE
     * @AjaxMethod=POST
     * @AjaxAsync=TRUE
     */
    function save_data() {

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

    function transfer_poi() {

        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $new = CL_MySQLi::get_instance();
        $old = new CL_MySQLi('localhost', 'root', '', 'ps_backup');

        $res = $old->query("SELECT *, AsText(latLng) AS latLngWKT, AsText(boundary) AS boundaryWKT FROM `poi`");
        while ($o = $res->fetchObject()) {
            $features = json_decode($o->features);
            if ($features === NULL) {
                echo $o->id . "<br />";
                $attrs = [];
            } else {
                $attrs = [
                    "description" => [
                        "details" => $features->description
                    ],
                    "sources" => [
                        "details" => $features->references
                    ]
                ];
            }
            POIModel::insert($o->id, $o->userId, $o->nearId, $o->countryId, $o->name, $o->label, $o->cat, $o->sub, LatLng::fromWKT($o->latLngWKT), Polygon::fromWKT($o->boundaryWKT), $attrs);
        }

        $old->close();
        $new->close();

        //print_r(POIModel::loadNearbys(new LatLng(44.044967, 15.106567)));
    }

}
