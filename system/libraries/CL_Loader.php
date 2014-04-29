<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class CL_Loader {

    private static $instance = NULL;
    private $out;

    private function CL_Loader() {
        $this->out = CL_Output::get_instance();
    }

    /**
     * @return CL_Loader
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Loader();
        }
        return self::$instance;
    }

    public function library($library) {

        if (!file_exists(APPPATH . 'libraries/' . $library . '.php')) {
            show_error("Requested library does not exist.", "Loader Error");
        }
        require_once APPPATH . 'libraries/' . $library . '.php';
    }

    public function test($test) {

        if (!file_exists(APPPATH . 'tests/' . $test . '.php')) {
            show_error("Requested test does not exist.", "Loader Error");
        }
        require_once APPPATH . 'tests/' . $test . '.php';
    }

    public function helper($helper) {

        if (!file_exists(SYSPATH . 'helpers/' . $helper . '.php')) {
            show_error("Requested helper does not exist.", "Loader Error");
        }
        require_once SYSPATH . 'helpers/' . $helper . '.php';
    }

    public function model($model) {

        if (!file_exists(APPPATH . 'models/' . $model . '.php')) {
            show_error("Requested model $model does not exist.", "Loader Error");
        }

        require_once APPPATH . 'models/' . $model . '.php';
    }

    public function view($view, $out = TRUE) {

        $contents = '';
        $viewPath = APPPATH . 'views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            show_error("View '$view' does not exist.", "Loader Error");
        }

        foreach ($this->out->vars as $var => $value) {
            ${$var} = $value;
        }

        ob_start();
        include $viewPath;
        $contents = ob_get_contents();
        if ($out)
            $this->out->append(ob_get_contents());
        ob_end_clean();

        return $contents;
    }

}

/* End of file CL_Loader.php */
/* Location: /system/libraries/CL_Loader.php */