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
        $host = $config->get_value('database', 'mysql_host');
        $user = $config->get_value('database', 'mysql_user');
        $password = $config->get_value('database', 'mysql_password');
        $database = $config->get_value('database', 'mysql_database');

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

        // Result will be false on error
        if ($this->res === FALSE) {
            $this->res = NULL;
            throw new CL_Exception($this->db->error);
        }

        return TRUE;
    }

    public function select($tbl, $what) {

        $args = func_get_args();

        // SELECT ? FROM ?
        if (count($args) == 2) {
            $query = $this->qb->buildSimpleSelect($tbl, $what);
        }

        // SELECT ? FROM ? WHERE ?
        if (count($args) === 3) {
            $query = $this->qb->buildSelect($tbl, $what, $args[2]);
        }

        // SELECT ? FROM ? JOIN ? WHERE ?
        if (count($args) === 4) {
            $query = $this->qb->buildJoinSelect($tbl, $what, $args[2], $args[3]);
        }

        return $this->query($query);
    }

    public function update($tbl, $set, $where) {
        $query = $this->qb->buildUpdate($tbl, $set, $where);
        return $this->query($query);
    }

    public function insert($tbl, $insert, $override = FALSE) {
        if ($override) {
            $query = $this->qb->buildInsertOverride($tbl, $insert);
        } else {
            $query = $this->qb->buildInsert($tbl, $insert);
        }
        return $this->query($query);
    }

    public function exists($tbl, $where) {
        $query = $this->qb->buildExists($tbl, $where);
        $this->query($query);
        $o = $this->fetchObject();
        return $o->numRows > 0;
    }

    public function numRows() {
        if ($this->res !== NULL) {
            return $this->res->num_rows;
        }
        return 0;
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

    /**
     * @return int Last insert id
     */
    public function getInsertId() {
        $this->db->insert_id;
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

    public function buildSimpleSelect($tbl, $what) {

        $tbl = $this->accentuateString($tbl);
        $what = $this->buildWhatFragment($what);

        return 'SELECT ' . $what . ' FROM ' . $tbl;
    }

    public function buildSelect($tbl, $what, $where) {

        $tbl = $this->accentuateString($tbl);
        $what = $this->buildWhatFragment($what);
        $where = $this->buildWhereFragment($where);

        return 'SELECT ' . $what . ' FROM ' . $tbl . ' WHERE ' . $where;
    }

    public function buildJoinSelect($tbl, $what, $join, $where) {

        $tbl = $this->accentuateString($tbl);
        $join = $this->buildJoinFragment($join);
        $what = $this->buildWhatFragment($what);
        $where = $this->buildWhereFragment($where);

        return 'SELECT ' . $what . ' FROM ' . $tbl . ' ' . $join . ' WHERE ' . $where;
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

    public function buildInsertOverride($tbl, $insert) {

        $tbl = $this->accentuateString($tbl);
        $cols = $this->buildColsFragment($insert);
        $values = $this->buildValuesFragment($insert);
        $set = $this->buildSetFragment($insert);

        return 'INSERT INTO ' . $tbl . ' (' . $cols . ') VALUES (' . $values . ') ON DUPLICATE KEY UPDATE ' . $set;
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

    public function buildJoinFragment($join) {

        $joins = [];

        foreach ($join AS $key => $value) {
            $joins[] = 'JOIN ' . $this->canonizeCol($key) . ' ON ' . $this->buildJoinWhereFragment($value);
        }

        return implode(' ', $joins);
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

    private function buildJoinWhereFragment($where) {
        $res = $this->buildJoinWhereFragments($where);
        return $res[0];
    }

    private function buildJoinWhereFragments($where) {

        foreach ($where AS $key => $value) {

            if ($key === 'AND') {
                $res = $this->buildJoinWhereFragments($value);
                $fragments[] = '(' . implode(' AND ', $res) . ')';
            } else if ($key === 'OR') {
                $res = $this->buildJoinWhereFragments($value);
                $fragments[] = '(' . implode(' OR ', $res) . ')';
            } else {
                $fragments[] = $this->accentuateCol($key) . ' = ' . $this->accentuateCol($value);
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

        if (strpos($col, '`') !== FALSE) {
            return $col;
        }

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
            case 'array': {
                    reset($value);
                    $fx = key($value);
                    return $fx . '()';
                }
            default: {
                    return $value;
                }
        }
    }

}
