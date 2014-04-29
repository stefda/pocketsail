<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class CL_Cache {

    private static $instance = NULL;
    private $uri = NULL;

    private function __construct() {
        $this->uri = CL_URI::get_instance();
    }

    /**
     * @return CL_Cache
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Cache();
        }
        return self::$instance;
    }

    public function is_cached() {

        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $cachedFileName = urlencode($url);
        $now = time();
        $cacheLifetime = 3600;
        $fileTime = @filemtime(BASEPATH . 'cache/' . $cachedFileName);

        if (!$fileTime || $now - $fileTime > $cacheLifetime) {
            touch(BASEPATH . 'cache/' . $cachedFileName);
            return FALSE;
        }
        return TRUE;
    }

    public function get() {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $fileName = urlencode($url);
        $html = file_get_contents(BASEPATH . 'cache/' . $fileName);
        return $html;
    }

    public function set($html) {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $fileName = urlencode($url);
        $fp = fopen(BASEPATH . 'cache/' . $fileName, 'w');
        fwrite($fp, $html);
        fclose($fp);
    }

}

/* End of file CL_Cache.php */
/* Location: /system/libraries/CL_Cache.php */