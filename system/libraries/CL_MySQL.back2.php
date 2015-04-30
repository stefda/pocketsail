<?php

/**
 * @author David Stefan
 */
class CL_MySQL {

    private static $instance = NULL;
    private $db = NULL;
    private $qb = NULL;
    private $res = NULL;
    private $query = '';

    public function __construct() {

        $config = CL_Config::get_instance();

        // Retrieve mysql config
        $host = $config->get_item('database', 'mysql_host');
        $user = $config->get_item('database', 'mysql_user');
        $password = $config->get_item('database', 'mysql_password');
        $database = $config->get_item('database', 'mysql_database');

        // Connect to db through mysqli
        $this->db = new mysqli($host, $user, $password, $database);

        // Hardcoded utf8 charset
        $this->db->set_charset('utf8');

        // Initialise fragment builder
        $this->qb = new CL_MySQL_QueryBuilder($this);
    }

    /**
     * @return CL_MySQL
     */
    public static function getInstance() {

        if (self::$instance === NULL) {
            self::$instance = new CL_MySQL();
        }

        return self::$instance;
    }

    /**
     * @param string $query
     * @return bool
     */
    public function query($query) {

        // Store query
        $this->query = $query;

        // Save query result
        $this->res = $this->db->query($query);

        // Result may be FALSE
        if ($this->res === FALSE) {
            $this->res = NULL;
            return FALSE;
        }

        return TRUE;
    }

    public function select($tbl, $what, $where) {
        $query = $this->qb->buildSelect($tbl, $what, $where);
        return $this->query($query);
    }

    public function update($tbl, $set, $where) {
        $query = $this->qb->buildUpdate($tbl, $set, $where);
        return $this->query($query);
    }

    public function insert($tbl, $insert) {
        $query = $this->qb->buildInsert($tbl, $insert);
        return $this->query($query);
    }

    public function exists($tbl, $where) {
        $query = $this->qb->buildExists($tbl, $where);
        $this->query($query);
        $o = $this->res->fetch_object();
        return $o->numRows > 0;
    }

    /**
     * @return mixed
     */
    public function fetchObject() {

        // Return NULL is no viable result exists
        if ($this->res === NULL) {
            return NULL;
        }

        // Otherwise redirect to native result's method
        return $this->res->fetch_object();
    }

    public function fetchAll() {

        // Return NULL is no viable result exists
        if ($this->res === NULL) {
            return NULL;
        }

        // Otherwise redirect to native result's method
        return $this->res->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchAllSimplify() {

        // Return NULL is no viable result exists
        if ($this->res === NULL) {
            return NULL;
        }

        // Simplify resulting array
        return array_map(function ($elem) {
            return $elem[0];
        }, $this->res->fetch_all(MYSQLI_NUM));
    }

    /**
     * @return string Last error
     */
    public function getError() {
        return $this->db->error;
    }

    /**
     * @return string Last query
     */
    public function getQuery() {
        return $this->query;
    }

    public function escapeString($str) {
        return mysqli_escape_string($this->db, $str);
    }

}

class CL_MySQL_QueryBuilder {

    private $mysql;

    public function __construct($mysql) {
        $this->mysql = $mysql;
    }

    public function buildSelect($tbl, $what, $where) {

        $tbl = $this->accentuateString($tbl);
        $what = $this->buildWhatFragment($what);
        $where = $this->buildWhereFragment($where);

        return 'SELECT ' . $what . ' FROM ' . $tbl . ' WHERE ' . $where;
    }

    public function buildUpdate($tbl, $set, $where) {

        $tbl = $this->accentuateString($tbl);
        $set = $this->buildSetFragment($set);
        $where = $this->buildWhereFragment($where);

        return 'UPDATE ' . $tbl . ' SET ' . $set . ' WHERE ' . $where;
    }

    public function buildInsert($tbl, $insert) {

        $tbl = $this->accentuateString($tbl);
        $cols = $this->buildColsFragment($insert);
        $values = $this->buildValuesFragment($insert);

        return 'INSERT INTO ' . $tbl . ' (' . $cols . ') VALUES (' . $values . ')';
    }

    public function buildExists($tbl, $where) {

        $tbl = $this->accentuateString($tbl);
        $where = $this->buildWhereFragment($where);

        return 'SELECT COUNT(*) AS `numRows` FROM ' . $tbl . ' WHERE ' . $where;
    }

    private function buildColsFragment($insert) {
        return '`' . implode('`, `', array_keys($insert)) . '`';
    }

    private function buildValuesFragment($insert) {

        $values = [];

        foreach ($insert AS $value) {
            $values[] = $this->prepareValue($value);
        }

        return implode(', ', $values);
    }

    private function buildSetFragment($set) {

        $assign = [];

        foreach ($set AS $col => $value) {
            $col = $this->accentuateCol($col);
            $value = $this->prepareValue($value);
            $assign[] = $col . ' = ' . $value;
        }

        return implode(', ', $assign);
    }

    private function buildWhatFragment($what) {

        if (gettype($what) !== 'array') {

            // Explode by comma
            $what = explode(',', trim($what));

            // If result has only one element
            if (count($what) === 1) {
                if ($what[0] === '*') {
                    return '*';
                } else {
                    //return $this->accentuateCol($what[0]);
                    return $this->canonizeCol($what[0]);
                }
            }
        }

        $cols = [];

        // Else iterate over all cols to canonize them
        foreach ($what AS $col) {
            $cols[] = $this->canonizeCol($col);
        }
        
        // And rebuild where clause from the resulting array
        return implode(', ', $cols);
    }

    private function buildWhereFragment($where) {
        $res = $this->buildWhereFragments($where);
        return $res[0];
    }

    private function buildWhereFragments($where) {

        foreach ($where AS $key => $value) {

            if ($key === 'AND') {
                $res = $this->buildWhereFragments($value);
                $fragments[] = '(' . implode(' AND ', $res) . ')';
            } else if ($key === 'OR') {
                $res = $this->buildWhereFragments($value);
                $fragments[] = '(' . implode(' OR ', $res) . ')';
            } else {
                $fragments[] = $this->buildColCondFragment($key) . $this->prepareValue($value);
            }
        }

        return $fragments;
    }

    private function buildColCondFragment($str) {

        $cond = '';
        $col = '';
        $match = []; // Initialise to shut NatBeans up
        // Try matching condition in brackets
        $res = preg_match('/\[([=,<,>]|<>|\!=)\]/', $str, $match);

        // If match fails, try 'equal' condition
        if (!$res) {
            $cond = '=';
            $col = $str;
        } else {
            // Otherwise, condition will be in an array with matches
            $cond = $match[1];
            // Now, remove the matched condition together with the brackets
            $col = preg_replace('/\[([=,<,>]|<>|\!=)\]/', '', $str);
        }

        return $this->accentuateCol($col) . ' ' . $cond . ' ';
    }

    private function accentuateCol($str) {
        $pieces = explode('.', $str);
        return '`' . implode('`.`', $pieces) . '`';
    }
    
    private function accentuateString($str) {
        return '`' . $str . '`';
    }
    
    private function canonizeCol($col) {
        
        $alias = NULL;
        $matches = NULL;
        
        // See if col contains alias in parentheses
        preg_match('/\((.*)\)/', $col, $matches);
        
        // If it does, extract it and remove from string
        if (count($matches) > 0) {
            $alias = $matches[1];
            $col = preg_replace('/\(.*\)/', '', $col);
            // Rebuild col reference together with alias
            return $this->accentuateCol($col) . ' AS ' . $this->accentuateString($alias);
        }
        
        // Accentuate col
        return $this->accentuateCol($col);
    }

    private function prepareValue($value) {

        switch (gettype($value)) {
            case 'string': {
                    $str = $this->mysql->escapeString($value);
                    return '\'' . $str . '\'';
                }
            case 'boolean': {
                    return $value ? 1 : 0;
                }
            case 'NULL': {
                    return 'NULL';
                }
            default: {
                    return $value;
                }
        }
    }

}
