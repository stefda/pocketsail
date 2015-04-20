<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class Home extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Security');
        Security::redirectWhenNotSignedIn();
    }
    
    function index() {
        $this->load->view('main');
    }
    
    function oops() {
        $this->load->view('error/oops');
    }
}