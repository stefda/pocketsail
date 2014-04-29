<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class CL_Controller {

    /**
     * @var CL_Loader
     */
    protected $load;

    /**
     * @var CL_Config
     */
    protected $config;

    /**
     * @var CL_Output
     */
    protected $out;
    
    /**
     * @var CL_Session
     */
    protected $session;

    public function __construct() {
        $this->load = CL_Loader::get_instance();
        $this->config = CL_Config::get_instance();
        $this->out = CL_Output::get_instance();
        $this->session = CL_Session::get_instance();
    }

    /**
     * Assigns given variable pair name-value to the output so that the variable
     * is made available for use in the loaded views.
     *
     * @param string $var
     * @param mixed $value
     */
    protected function assign($var, $value) {
        $this->out->assign_vars(array($var => $value));
    }

    /**
     * Assigns given variables to the output so that those are then available
     * for use in the loaded views.
     *
     * @param array $vars
     */
    protected function assign_variables($vars) {
        $this->out->assign_vars($vars);
    }

    /**
     * Redirects browser to the given uri using php-native header function.
     *
     * @param string $uri
     */
    protected function location($uri) {
        location($uri);
    }
    
    protected function message($referer) {
        $this->session->set('referer', $referer);
        $this->location('/message');
    }

    /**
     * Assigns a javascript snippet with the given variables to the output. The
     * snippet is then sent to the browser along with other views so that the
     * views loaded after calling this function can use the variables in it.
     *
     * @param array $vars
     */
    protected function assign_js($vars) {
        $this->out->assign_js($vars);
    }

    public function end() {
        // TO BE OVERRIDEN
    }

}

/* End of file CL_Controller.php */
/* Location: /system/libraries/CL_Controller.php */