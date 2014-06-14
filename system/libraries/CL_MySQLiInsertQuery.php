<?php

class CL_MySQLiInsertQuery {

    private $mysql;
    private $table;
    private $valuesBuffer;

    public function __construct(CL_MySQLi $mysql) {
        $this->mysql = $mysql;
        $this->table = "";
        $this->valuesBuffer = [];
    }

    public function into($table) {
        $table = trim($table);
        $this->table = "`$table`";
        return $this;
    }

    public function value($name, $value) {
        switch (gettype($value)) {
            case 'string':
                $value = $this->mysql->escape_string($value);
                $value = "'$value'";
                break;
            case 'NULL':
                $value = 'NULL';
                break;
        }
        $this->valuesBuffer[$name] = $value;
        return $this;
    }

    public function op($op) {
        $value = end($this->valuesBuffer);
        $name = key($this->valuesBuffer);
        $this->valuesBuffer[$name] = "$op($value)";
        return $this;
    }

    /**
     * @return CL_MySQLiResult
     */
    public function exec() {
        return $this->mysql->query($this);
    }

    public function __toString() {
        $names = array_keys($this->valuesBuffer);
        $values = array_values($this->valuesBuffer);
        $query = "INSERT INTO $this->table (`" . implode("`,`", $names) . "`) VALUES (" . implode(",",
                        $values) . ")";
        return $query;
    }

}
