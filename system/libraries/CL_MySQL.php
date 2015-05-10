<?php

class CL_MySQL extends CL_Database {

    private static $instance = NULL;

    public function __construct() {
        parent::__construct('mysql');
    }

    /**
     * @return CL_MySQL
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            return self::$instance = new CL_MySQL();
        }
        return self::$instance;
    }

}
