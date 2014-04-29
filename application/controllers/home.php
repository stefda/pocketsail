<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class Home extends CL_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->load->view('map');
    }
}