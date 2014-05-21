<?php

class MySQLConnection {

    private static $instance = null;
    private $mysql = null;

    private function __construct() {
        $this->mysql = new mysqli('localhost', 'root', '', 'pocketsail');
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new MySQLConnection();
        }
        return self::$instance;
    }

    public function prepare_statement($query) {
        if (($stmt = $this->mysql->prepare($query)) === FALSE) {
            throw new MySQLException($this->mysql->error, $this->mysql->errno);
        }
        return new MySQLStatement($stmt);
    }
    
    public function close() {
        return $this->mysql->close();
    }

}
