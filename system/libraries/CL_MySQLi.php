<?php

// Aliases
function select($mysql) {
    return new CL_MySQLiSelectQuery($mysql);
}

function insert($mysql) {
    return new CL_MySQLiInsertQuery($mysql);
}

function update($mysql) {
    return new CL_MySQLiUpdateQuery($mysql);
}

// Constants
define('EQ', '=');
define('GT', '>');
define('LT', '<');
define('IS', 'IS');
define('IN', 'IN');
define('GEOM_FROM_TEXT', 'GeomFromText');
define('AS_TEXT', 'AsText');

class CL_MySQLi {

    private static $instance = null;
    private $mysql = null;

    private function __construct() {
        $this->mysql = new mysqli('localhost', 'root', '', 'pocketsail');
    }

    /**
     * @return CL_MySQLi
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new CL_MySQLi();
        }
        return self::$instance;
    }

    /**
     * @param string $query
     * @return \CL_MySQLiResult
     * @throws CL_Exception
     */
    public function query($query) {
        $result = $this->mysql->query($query);
        if (!$result) {
            $trace = debug_backtrace();
            $file = $trace[0]['file'];
            $line = $trace[0]['line'];
            throw new CL_Exception($this->mysql->error . ", called in $file on line $line. $query");
        }
        return new CL_MySQLiResult($result);
    }
    
    public function insert() {
        return new CL_MySQLiInsertQuery($this);
    }
    
    public function update() {
        return new CL_MySQLiUpdateQuery($this);
    }

    public function select() {
        return new CL_MySQLiSelectQuery($this);
    }

    public function escape_string($string) {
        return $this->mysql->real_escape_string($string);
    }

    public function insertId() {
        return $this->mysql->insert_id;
    }

    public function close() {
        if ($this->mysql !== NULL) {
            $this->mysql->close();
            $this->mysql = NULL;
        }
    }

    public function __destruct() {
        $this->close();
    }

}
