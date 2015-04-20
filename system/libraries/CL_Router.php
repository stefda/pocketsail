<?php

class CL_Router {

    private static $instance = NULL;
    private $uri;
    private $class = null;
    private $method = null;
    private $parameters = NULL;

    private function __construct() {
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

        // Get default class if not provided
        if (count($this->uri->segments) == 0) {
            if (trim(get_config_value('main', 'default_controller')) == '') {
                error("Default controller is not defined.");
            }
            return strtolower(get_config_value('main', 'default_controller'));
        }

        // Do routing
        $class = strtolower($this->uri->segments[0]);
        $route = get_config_value('routes', $class);

        if ($route !== NULL) {
            array_shift($this->uri->segments);
            $routeSegments = explode("/", $route);
            array_unshift($this->uri->segments, $routeSegments[0], $routeSegments[1]);
        }

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
