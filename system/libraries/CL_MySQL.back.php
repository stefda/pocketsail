<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class CL_MySQL {

    private static $instance = NULL;
    private $config;

    /**
     * @var mysqli
     */
    private $connection;

    private function CL_MySQL() {
        $this->config = CL_Config::get_instance();
        $this->connection = NULL;
    }

    /**
     * @return CL_MySQL
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_MySQL();
        }
        return self::$instance;
    }

    public function connect() {

        if ($this->connection == NULL) {
            $server = $this->config->get_value('database', 'server');
            $username = $this->config->get_value('database', 'username');
            $password = $this->config->get_value('database', 'password');
            $database = $this->config->get_value('database', 'database');
            //$this->connection = mysql_connect($server, $username, $password);
            //$this->connection = mysql_pconnect($server, $username, $password);
            $this->connection = new mysqli($server, $username, $password, $database);
            $this->connection->set_charset('utf8');
            //mysql_set_charset('utf8');
            //mysql_select_db($database);
        }
    }

    public function close() {
        if ($this->connection !== NULL) {
//            mysql_close($this->connection);
            $this->connection->close();
            $this->connection = NULL;
        }
    }

    public function __destruct() {
        $this->close();
    }

    public function query($q) {
        if ($this->connection === NULL) {
            $this->connect();
        }
        $result = $this->connection->query($q);
        //$result = mysql_query($q);
        //$result = $this->connection->query;
        if (!$result) {
            $trace = debug_backtrace();
            $file = $trace[0]['file'];
            $line = $trace[0]['line'];
            throw new CL_Exception($this->connection->error . ", called in $file on line $line. $q");
        }
        return $result;
    }

    public function insert($table, $data) {

        $keysSQL = '(';
        $valuesSQL = 'VALUES(';

        $i = 0;
        $len = count($data);
        foreach ($data AS $key => $value) {
            $keysSQL .= "`$key`";
            $valuesSQL .= $this->to_sql($value);
            if ($i++ !== $len - 1) {
                $keysSQL .= ',';
                $valuesSQL .= ',';
            } else {
                $keysSQL .= ')';
                $valuesSQL .= ')';
            }
        }
        return $this->connection->query("INSERT INTO `$table` $keysSQL $valuesSQL");
    }

    public function update($table, $whereSQL, $data) {

        $setSQL = 'SET ';

        $i = 0;
        $len = count($data);
        foreach ($data AS $key => $item) {
            $setSQL .= $this->to_sql_set_item($key, $item);
            if ($i++ !== $len - 1) {
                $setSQL .= ',';
            }
        }
        return $this->connection->query("UPDATE `$table` $setSQL WHERE $whereSQL");
    }

    private function to_sql($item) {
        if ($this->connection === null) {
            $this->connect();
        }
        switch (gettype($item)) {
            case 'string': {
                    $val = mysqli_escape_string($this->connection, $item);
                    return "'$val'";
                }
            case 'double':
            case 'integer': {
                    return "$item";
                }
            case 'NULL': {
                    return "NULL";
                }
            case 'array': {
                    return "'" . mysqli_escape_string($this->connection, json_encode($item)) . "'";
                }
            case 'object': {
                    if (get_class($item) === 'stdClass') {
                        return "'" . mysqli_escape_string($this->connection, json_encode($item)) . "'";
                    }
                    if (!method_exists($item, 'toSQL')) {
                        $trace = debug_backtrace();
                        $file = $trace[1]['file'];
                        $line = $trace[1]['line'];
                        throw new CL_Exception("Item object doesn't have method toSQL(). Called in $file on line $line.");
                    }
                    return $item->toSQL();
                }
        }
    }

    private function to_sql_set_item($key, $item) {
        return "`$key` = " . $this->to_sql($item);
    }

    public function num_rows(mysqli_result $r) {
        //return mysql_num_rows($r);
        return $r->num_rows;
    }

    public function affected_rows() {
        return mysql_affected_rows();
    }

    public function fetch_object($r) {
        return $r->fetch_object();
        //return mysql_fetch_object($r);
    }

    public function fetch_array(mysqli_result $r) {
        //return mysql_fetch_array($r);
        return $r->fetch_array();
    }

    public function insert_id() {
        //return mysql_insert_id();
        return $this->connection->insert_id;
    }

    public function begin() {
        $this->query('START TRANSACTION');
    }

    public function rollback() {
        $this->query('ROLLBACK');
    }

    public function commit() {
        $this->query('COMMIT');
    }

}

/* End of file CL_MySQL.php */
/* Location: /system/libraries/CL_MySQL.php */