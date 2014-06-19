<?php

class Security {

    public static function redirectWhenNotSignedIn() {

        $session = CL_Session::get_instance();
        $id = $session->get('signedId');

        if ($id === FALSE) {
            location('/user/login');
            exit();
        }
    }

}
