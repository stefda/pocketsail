<?php

class CL_MySQLiUpdateQuery {

    private $mysql;
    private $table;
    private $valuesBuffer;
    private $condsBuffer;

    public function __construct(CL_MySQLi $mysql) {
        $this->mysql = $mysql;
        $this->table = "";
        $this->valuesBuffer = [];
    }

    public function table($table) {
        $table = trim($table);
        $this->table = "`$table`";
        return $this;
    }

    public function set() {
        $args = func_get_args();
        if (count($args) === 2) {
            $value = $this->prepareValue($args[1]);
            $this->valuesBuffer[$args[0]] = $value;
        } else {
            $this->valuesBuffer[$args[0]] = NULL;
        }
        return $this;
    }

    public function op($op) {
        $value = end($this->valuesBuffer);
        $name = key($this->valuesBuffer);
        $this->valuesBuffer[$name] = "$op($value)";
        return $this;
    }

    public function where() {
        $args = func_get_args();
        if (count($args) === 1) {
            $this->condsBuffer[] = $args[0];
        }
        if (count($args) === 3) {
            $value = $this->prepareValue($args[2]);
            $cond = "`$args[0]` $args[1] $value";
            $this->condsBuffer[] = $cond;
        }
        return $this;
    }

    public function andCond() {
        $args = func_get_args();
        if (count($args) === 1) {
            $this->condsBuffer[] = "AND $args[0]";
        }
        if (count($args) === 3) {
            $value = $this->prepareValue($args[2]);
            $cond = "`$args[0]` $args[1] $value";
            $this->condsBuffer[] = "AND $cond";
        }
        return $this;
    }

    public function orCond() {
        $args = func_get_args();
        if (count($args) === 1) {
            $this->condsBuffer[] = "OR $args[0]";
        }
        if (count($args) === 3) {
            $value = $this->prepareValue($args[2]);
            $cond = "`$args[0]` $args[1] $value";
            $this->condsBuffer[] = "OR $cond";
        }
        return $this;
    }

    public function prepareValue($value) {
        switch (gettype($value)) {
            case 'string':
                $value = $this->mysql->escape_string($value);
                $value = "'$value'";
                break;
            case 'NULL':
                $value = 'NULL';
                break;
            case 'array':
                array_walk($value,
                        function(&$item) {
                    $item = $this->prepareValue($item);
                });
                $value = "(" . implode(",", $value) . ")";
                break;
        }
        return $value;
    }

    public function exec() {
        return $this->mysql->query($this);
    }

    public function __toString() {
        $query = "UPDATE $this->table SET ";
        $counter = 0;
        $valuesCount = count($this->valuesBuffer);
        foreach ($this->valuesBuffer AS $name => $value) {
            $query .= "`$name` = $value";
            $query .= ++$counter === $valuesCount ? "" : ", ";
        }
        $query .= " WHERE " . implode(" ", $this->condsBuffer);
        return $query;
    }

}
