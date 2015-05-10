<?php

class Mobile extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->view('mobile');
    }

}
