<?php

class CL_URI {

    private static $instance = null;
    public $string = '';
    public $segments = array();

    private function __construct() {
        $this->fetch_uri_string();
        $this->parse_uri_segments();
    }

    /**
     * @return CL_Uri
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new CL_URI();
        }
        return self::$instance;
    }

    public function set_uri($uriString) {
        array_shift($this->segments);
        $this->string = $uriString . '/' . implode('/', $this->segments);
        $this->segments = array();
        $this->parse_uri_segments();
    }

    public function replace($string) {
        $this->segments[0] = $string;
        $this->string = implode('/', $this->segments);
    }

    public function shift() {
        array_shift($this->segments);
        $this->string = implode('/', $this->segments);
    }

    private function fetch_uri_string() {

        $path = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : @getenv('REQUEST_URI');
        $path = ($trimmedPath = mb_strstr($path, '?', TRUE)) !== FALSE ? $trimmedPath : $path;

        $urlSplit = explode('.', $_SERVER['HTTP_HOST'], 3);

        $urlSplit[0] == 'www' ? array_shift($urlSplit) : null;

        if (trim($path, '/') != '' && $path != "/" . SELF) {
            $this->string = ltrim($path, '/');
            return;
        }
    }

    private function parse_uri_segments() {

        foreach (explode('/', $this->string) as $segment) {
            $this->segments[] = urldecode($segment);
        }

        if (end($this->segments) == '') {
            array_pop($this->segments);
        }
    }

}