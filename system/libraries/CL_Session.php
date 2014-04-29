<?php

if (!defined('SYSPATH')) exit("No direct script access allowed!");

class CL_Session {

    private static $instance = NULL;

    private function CL_Session() {
        session_start();
    }

    /**
     * @return CL_Session
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Session();
        }
        return self::$instance;
    }

    public function get($key) {
        if (!key_exists($key, $_SESSION)) {
            return FALSE;
        }
        return $_SESSION[$key];
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function is_set($key) {
        return key_exists($key, $_SESSION);
    }

    public function remove($key) {
        if (key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
}


/* End of file CL_Session.php */
/* Location: /system/libraries/CL_Session.php */