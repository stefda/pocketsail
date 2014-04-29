<?php

if (!defined('SYSPATH')) exit("No direct script access allowed!");

class CL_Log {

    private static $instance = NULL;

    private function CL_Log() {
    }

    /**
     * @return CL_Log
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Log();
        }
        return self::$instance;
    }

    public function write($message) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $uri = $_SERVER['REQUEST_URI'];
        $referer = key_exists('HTTP_REFERER', $_SERVER) ? ' REF:' . $_SERVER['HTTP_REFERER'] : '';
        $time = date('d/m/y H:i:s');
        $fp = fopen(SYSPATH . 'logs/error.log', 'a');
        fwrite($fp, "$time$referer [$ip] [$uri] $message\n");
        fclose($fp);
    }
}


/* End of file CL_Log.php */
/* Location: /system/libraries/CL_Log.php */