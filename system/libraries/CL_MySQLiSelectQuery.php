<?php

class CL_MySQLiSelectQuery {

    private $mysql;
    private $table;
    private $alias;
    private $colsBuffer;
    private $joinsBuffer;
    private $joinTypesBuffer;
    private $condsBuffer;
    private $lastCommand;
    private $lastJoinAlias;
    private $order;
    private $group;
    private $useTableName;

    public function __construct(CL_MySQLi $mysql) {
        $this->mysql = $mysql;
        $this->table = "";
        $this->alias = "";
        $this->colsBuffer = [];
        $this->joinsBuffer = [];
        $this->joinTypesBuffer = [];
        $this->condsBuffer = [];
        $this->lastCommand = NULL;
        $this->order = "";
        $this->group = "";
        $this->lastJoinAlias = NULL;
    }

    public function all($alias = "") {
        $alias = $alias !== "" ? "`$alias`." : "";
        $this->colsBuffer[] = "$alias*";
        $this->lastCommand = NULL;
        return $this;
    }

    public function col($name, $alias = "") {
        $alias = $alias !== "" ? "`$alias`." : "";
        $this->colsBuffer[] = "$alias`$name`";
        $this->lastCommand = 'col';
        return $this;
    }

    public function op($op) {
        $col = end($this->colsBuffer);
        $key = key($this->colsBuffer);
        $this->colsBuffer[$key] = "$op($col)";
        return $this;
    }

    public function from($table) {
        $this->table = "`$table`";
        $this->lastCommand = 'from';
        return $this;
    }

    public function join($table) {
        $this->joinsBuffer[] = "`$table`";
        $this->joinTypesBuffer[] = "JOIN";
        $this->lastCommand = 'join';
        $this->lastJoinAlias = $table;
        return $this;
    }

    public function leftJoin($table) {
        $this->joinsBuffer[] = "`$table`";
        $this->joinTypesBuffer[] = "LEFT JOIN";
        $this->lastCommand = 'join';
        $this->lastJoinAlias = $table;
        return $this;
    }

    public function on() {
        $args = func_get_args();
        if (count($args) === 1) {
            $col = $args[0];
            $joinAlias = "`$this->lastJoinAlias`.";
            $tableAlias = $this->alias !== '' ? "`$this->alias`." : "$this->table.";
            $cond = "$joinAlias`$col` = $tableAlias`$col`";
        }
        if (count($args) === 2) {
            $joinCol = $args[0];
            $tableCol = $args[1];
            $joinAlias = "`$this->lastJoinAlias`.";
            $tableAlias = $this->alias !== '' ? "`$this->alias`." : "$this->table.";
            $cond = "$joinAlias`$joinCol` = $tableAlias`$tableCol`";
        }
        $join = end($this->joinsBuffer);
        $key = key($this->joinsBuffer);
        $this->joinsBuffer[$key] = "$join ON $cond";
        return $this;
    }

    public function alias($alias) {
        switch ($this->lastCommand) {
            case 'col':
                $col = end($this->colsBuffer);
                $key = key($this->colsBuffer);
                $this->colsBuffer[$key] = "$col AS `$alias`";
                break;
            case 'from':
                $this->table = "$this->table AS `$alias`";
                $this->alias = $alias;
                break;
            case 'join':
                $join = end($this->joinsBuffer);
                $key = key($this->joinsBuffer);
                $this->joinsBuffer[$key] = "$join AS `$alias`";
                $this->lastJoinAlias = $alias;
                break;
        }
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
        if (count($args) === 4) {
            $value = $this->prepareValue($args[3]);
            $cond = "`$args[1]`.`$args[0]` $args[2] $value";
            $this->condsBuffer[] = $cond;
        }
        return $this;
    }

    public function andCond() {
        $args = func_get_args();
        if (count($args) === 1) {
            if ($args[0] === '' || $args[0] === NULL) {
                return $this;
            }
            $this->condsBuffer[] = "AND $args[0]";
        }
        if (count($args) === 3) {
            $value = $this->prepareValue($args[2]);
            $cond = "`$args[0]` $args[1] $value";
            $this->condsBuffer[] = "AND $cond";
        }
        if (count($args) === 4) {
            $value = $this->prepareValue($args[3]);
            $cond = "`$args[1]`.`$args[0]` $args[2] $value";
            $this->condsBuffer[] = "AND $cond";
        }
        return $this;
    }

    public function orCond() {
        $args = func_get_args();
        if (count($args) === 1) {
            if ($args[0] === '' || $args[0] === NULL) {
                return $this;
            }
            $this->condsBuffer[] = "OR $args[0]";
        }
        if (count($args) === 3) {
            $value = $this->prepareValue($args[2]);
            $cond = "`$args[0]` $args[1] $value";
            $this->condsBuffer[] = "OR $cond";
        }
        if (count($args) === 4) {
            $value = $this->prepareValue($args[3]);
            $cond = "`$args[1]`.`$args[0]` $args[2] $value";
            $this->condsBuffer[] = "OR $cond";
        }
        return $this;
    }

    public function orderBy($col) {
        $this->order = $col;
        return $this;
    }

    public function groupBy($col) {
        $this->group = $col;
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
                if (count($value) === 0) {
                    return "('')";
                }
                array_walk($value, function(&$item) {
                    $item = $this->prepareValue($item);
                });
                $value = "(" . implode(",", $value) . ")";
                break;
        }
        return $value;
    }

    /**
     * @return CL_MySQLiResult
     */
    public function exec() {
        return $this->mysql->query($this);
    }

    public function __toString() {
        $query = "SELECT " . implode(", ", $this->colsBuffer) . " FROM $this->table";
        $counter = 0;
        if (count($this->joinsBuffer) > 0) {
            foreach ($this->joinsBuffer AS $join) {
                $query .= " " . $this->joinTypesBuffer[$counter++] . " $join";
            }
        }
        if (count($this->condsBuffer) > 0) {
            $query .= " WHERE " . implode(" ", $this->condsBuffer);
        }
        if ($this->order !== "") {
            $query .= " ORDER BY `$this->order`";
        }
        if ($this->group !== "") {
            $query .= " GROUP BY `$this->group`";
        }
        return $query;
    }

}
