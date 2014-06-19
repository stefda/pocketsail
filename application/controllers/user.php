<?php

class User extends CL_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function login() {
        $this->load->view('login');
    }

    public function do_login() {

        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if ($user === 'admin' && $password === 'potkan-sail') {
            CL_Session::get_instance()->set('signedId', 1);
            location('/');
        } else {
            location('/user/login?error=3');
        }
    }

    public function do_logout() {
        CL_Session::get_instance()->remove('signedId');
        location('/user/login');
    }

}
