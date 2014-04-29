<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class CL_Router {

    private static $instance = NULL;
    private $config;
    private $uri;
    private $class = null;
    private $method = null;
    private $section = null;
    private $page = null;
    private $parameters = NULL;

    private function __construct() {
        $this->config = CL_Config::get_instance();
        $this->uri = CL_URI::get_instance();
    }

    /**
     * @return CL_Router
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Router();
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    public function fetch_class() {

        if ($this->class != null) {
            return $this->class;
        }

        // In case uri segments array is empty, try to fetch controller name
        // from the config object...
        if (count($this->uri->segments) == 0) {
            if (trim($this->config->get_item('main', 'default_controller')) == '') {
                show_error("Default controller is not defined.", "Router Error");
            }
            return strtolower($this->config->get_item('main', 'default_controller'));
        }

        // ...otherwise return first segment of the uri segments array
        $this->class = strtolower($this->uri->segments[0]);
        return $this->class;
    }

    public function fetch_method() {

        if ($this->method != null) {
            return $this->method;
        }

        // Well, if method name isn't present use index instead
        $this->method = count($this->uri->segments) > 1 && $this->uri->segments[1] != '' ? $this->uri->segments[1] : 'index';

        return $this->method;
    }

    public function fetch_parameters() {

        if ($this->parameters !== NULL) {
            return $this->parameters;
        }

        if (count($this->uri->segments) < 3) {
            $this->parameters = array();
        } else {
            $this->parameters = array_slice($this->uri->segments, 2, count($this->uri->segments));
        }

        return $this->parameters;
    }

}

/* End of file CL_Router.php */
/* Location: /system/libraries/CL_Router.php */