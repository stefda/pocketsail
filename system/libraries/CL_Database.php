<?php

class CL_Database {

    protected $conn = NULL;

    protected function __construct($type) {

        $host = get_config_value('database', "{$type}_host");
        $user = get_config_value('database', "{$type}_user");
        $password = get_config_value('database', "{$type}_password");
        $database = get_config_value('database', "{$type}_database");

        $this->conn = $this->connect($type, $host, $user, $password, $database);
    }

    public function __destruct() {
        $this->conn = NULL;
    }

    /**
     * @param string $type
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @return PDO
     */
    private function connect($type, $host, $user, $password, $database) {
        $conn = new PDO("$type:host=$host;dbname=$database;charset=utf8", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $conn;
    }

    public function query($query) {
        try {
            return new CL_DatabaseStatement($this->conn->query($query));
        } catch (PDOException $e) {
            error($e->getMessage());
        }
    }

    public function prepare($query) {
        try {
            return new CL_DatabaseStatement($this->conn->prepare($query));
        } catch (Exception $e) {
            error($e->getMessage());
        }
    }

    public function execute($query, $params = []) {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch_all($query, $params = []) {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch_all();
    }

    public function insert($table, $params) {

        // Build columns string
        //$cols = '`' . implode('`, `', array_keys($params)) . '`';
        $cols = implode(', ', array_keys($params));

        // Build values string
        $vals = implode(', ', array_fill(0, count($params), '?'));

        // Build final query
        $query = "INSERT INTO `$table` ($cols) VALUES ($vals)";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute(array_values($params));
    }

    public function update($table, $cols, $cond) {

        // Build "set" query
        $set = '`' . implode('` = ?, `', array_keys($cols)) . '` = ?';

        // Build "where" condition
        $where = '`' . implode('` = ? AND `', array_keys($cond)) . '` = ?';

        // Merge params
        $params = array_values($cols);
        $params = array_merge($params, array_values($cond));

        // Build final query
        $query = "UPDATE `$table` SET $set WHERE $where";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function last_insert_id() {
        return $this->conn->lastInsertId();
    }

}

class CL_DatabaseStatement {

    private $stmt;

    public function __construct(PDOStatement $stmt) {
        $this->stmt = $stmt;
    }

    public function execute($params) {
        try {
            return $this->stmt->execute($params);
        } catch (PDOException $e) {
            error($e);
        }
    }

    public function fetch_all($fetchStyle = NULL) {
        try {
            return $this->stmt->fetchAll($fetchStyle);
        } catch (PDOException $e) {
            error($e->getMessage());
        }
    }

    public function fetch($fetchStyle = NULL) {
        try {
            return $this->stmt->fetch($fetchStyle);
        } catch (PDOException $e) {
            error($e->getMessage());
        }
    }

}
