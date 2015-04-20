<?php

/**
 * @author David Stefan
 */
class CL_PgSQL extends CL_Database {

    private static $instance = NULL;

    public function __construct() {
        parent::__construct('pgsql');
    }

    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_PgSQL();
        }
        return self::$instance;
    }

}
