<?php

class Home extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->view('main');
    }

}
