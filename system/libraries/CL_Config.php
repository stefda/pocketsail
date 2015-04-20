<?php

class CL_Config {

    private static $instance = NULL;
    private $config;

    private function __construct() {
        $this->set_config();
    }

    /**
     * @return CL_Config
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Config();
        }
        return self::$instance;
    }

    private function set_config() {
        $this->config['main'] = & get_config();
    }

    public function load_config($file) {
        $this->config[$file] = & get_config($file);
    }

    public function get_config($file = 'main') {

        if (!isset($this->config[$file])) {
            $this->load_config($file);
        }

        return $this->config[$file];
    }

    public function get_value($file, $key) {
        $config = $this->get_config($file);
        $item = @$config[$key];
        return $item === NULL ? NULL : $item;
    }

}
