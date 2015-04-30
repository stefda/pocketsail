<?php

/**
 * @author David Stefan
 */
class CL_Database {

    private $conn = NULL;

    public function __construct($type) {

        if (!in_array($type, ['pgsql', 'mysql'])) {
            throw new Exception("Database type " . $type . " is not supported.");
        }

        $config = CL_Config::get_instance();

        // Retrieve mysql config
        $host = $config->get_value('database', "{$type}_host");
        $user = $config->get_value('database', "{$type}_user");
        $password = $config->get_value('database', "{$type}_password");
        $database = $config->get_value('database', "{$type}_database");

        $dbalConfig = new Doctrine\DBAL\Configuration();

        $connParams = array(
            'dbname' => $database,
            'user' => $user,
            'password' => $password,
            'host' => $host,
            'driver' => "pdo_{$type}"
        );

        $this->conn = Doctrine\DBAL\DriverManager::getConnection($connParams, $dbalConfig);
    }
    
    public function begin() {
        $this->conn->beginTransaction();
    }
    
    public function commit() {
        $this->conn->commit();
    }

    public function execute_query($sql, $params = []) {
        if ($this->conn !== NULL) {
            return new CL_Statement($this->conn->executeQuery($sql, $params));
        }
        return FALSE;
    }

    public function fetch_all($sql, $params = []) {
        if ($this->conn !== NULL) {
            return $this->conn->fetchAll($sql, $params);
        }
        return FALSE;
    }

    public function execute_update($sql, $params = []) {
        if ($this->conn !== NULL) {
            return $this->conn->executeUpdate($sql, $params);
        }
        return FALSE;
    }

    public function insert($table, $data) {
        if ($this->conn !== NULL) {
            return $this->conn->insert($table, $data);
        }
        return FALSE;
    }

    public function update($table, $data, $match) {
        if ($this->conn !== NULL) {
            return $this->conn->update($table, $data, $match);
        }
        return FALSE;
    }
    
    public function insert_id() {
        return $this->conn->lastInsertId();
    }

}

class CL_Statement {

    private $stmt;

    public function __construct(PDOStatement $stmt) {
        $this->stmt = $stmt;
    }

    /**
     * Retrieves the next row from the statement or false if there are none.
     * Moves the pointer forward one row, so that consecutive calls will always
     * return the next row.
     * 
     * @return Associative array
     */
    public function fetch() {
        return $this->stmt->fetch();
    }

}
