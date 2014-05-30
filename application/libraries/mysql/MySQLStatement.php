<?php

class MySQLStatement {

    private $stmt = null;
    private $row = null;

    public function __construct(mysqli_stmt $stmt) {
        $this->stmt = $stmt;
    }

    public function bind_param($params) {
        $types = '';
        $vars = [];
        for ($i = 0; $i < count($params); $i++) {
            switch (gettype($params[$i])) {
                case 'integer':
                case 'boolean':
                    $types .= 'i';
                    break;
                case 'string':
                    $types .= 's';
                    break;
                case 'double':
                    $types .= 'd';
                    break;
                case 'NULL':
                    $types .= 'b';
                    break;
            }
            $vars[] = &$params[$i];
        }
        array_unshift($vars, $types);
        return call_user_func_array([$this->stmt, 'bind_param'], $vars);
    }

    public function execute() {
        $metadata = $this->stmt->result_metadata();
        if (!$metadata) {
            return $this->stmt->execute();
        }
        $fields = $this->stmt->result_metadata()->fetch_fields();
        $this->row = new stdClass();
        $bind = [];
        foreach ($fields AS $field) {
            $this->row->{$field->name} = null;
            $bind[] = &$this->row->{$field->name};
        }
        call_user_func_array([$this->stmt, "bind_result"], $bind);
        if (!$this->stmt->execute()) {
            throw new MySQLException($this->stmt->error, $this->stmt->errno);
        }
        return TRUE;
    }

    public function fetch() {
        if (!$this->stmt->fetch()) {
            return FALSE;
        }
        return $this->row;
    }
    
    public function close() {
        $this->stmt->close();
    }

}
