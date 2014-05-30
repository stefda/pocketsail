<?php

define('EQ', '=');
define('NEQ', '!=');
define('GT', '>');
define('LT', '<');
define('NOT_IN', 'NOT_IN');

function q() {
    return new MySQLQuery();
}


//        $q = select()
//                ->all('poi')
//                ->col('latLng', 'poi')->asText()->as('latLngWKT')
//                ->col('border', 'poi')->asText()->as('borderWKT')
//                ->col('name', 'poiNear')->as('nearName')
//                ->col('name', 'poiCountry')->as('countryName')
//                ->col('name', 'poiType')->as('subName')
//                //
//                ->from('poi')->as('poi')
//                //
//                ->leftJoin('poi')->as('poiNear')->on('id', 'nearId')
//                ->leftJoin('poi')->as('poiCountry')->on('id', 'countryId')
//                ->leftJoin('poi_type')->as('poiType')->ont('id', 'sub')
//                //
//                ->where('id', EQ, $id)
//                ->orderBy('name');

class MySQLQuery {

    private $q;
    private $alias;

    public function __construct() {
        $this->q = '';
        $this->alias = '';
    }

    public function select() {
        $args = func_get_args();
        $this->q = "SELECT ";
        if (count($args) === 0) {
            $this->q .= "*";
        } elseif (count($args) === 1) {
            if (gettype($args[0]) === 'array') {
                $this->q .= "`" . implode("`,`", $args[0]) . "`";
            } else {
                $this->q .= trim($args[0]);
            }
        } elseif (count($args) > 1 && count($args) % 2 === 0) {
            for ($i = 0; $i < count($args); $i += 2) {
                for ($j = 0; $j < count($args[$i]); $j++) {
                    $this->q .= "`" . $args[$i + 1] . "`.`" . $args[$i][$j] . "`";
                    $this->q .= $j === count($args[$i]) - 1 ? "" : ",";
                }
                $this->q .= $i === count($args) - 2 ? "" : ",";
            }
        }
        return $this;
    }

    public function from() {
        $args = func_get_args();
        $this->q .= " FROM `" . trim($args[0]) . "`";
        if (count($args) === 2) {
            $this->alias = "`" . $args[1] . "`.";
            $this->q .= " AS `" . $args[1] . "`";
        }
        return $this;
    }

    public function join($what) {
        $this->q .= " JOIN " . trim($what);
        return $this;
    }

    public function on($what) {
        $this->q .= " ON " . trim($what);
        return $this;
    }

    public function where($var, $op, $val, $alias = "") {
        $alias = $alias === '' ? $this->alias : "`" . $alias . "`";
        $this->q .= " WHERE " . $alias . "`" . trim($var) . "` " . $op . " " . $this->escapeVar($val);
        return $this;
    }

    public function und($what) {
        if (trim($what) !== "") {
            $this->q .= " AND " . trim($what);
        }
        return $this;
    }

    public function oder($what) {
        if (trim($what) !== "") {
            $this->q .= " OR " . trim($what);
        }
        return $this;
    }

    public function orderBy($byWhat) {
        $this->q .= " ORDER BY " . trim($byWhat);
        return $this;
    }
    
    public function escapeVar($var) {
        if (gettype($var) === 'string') {
            return "'" . mysql_real_escape_string($var) . "'";
        }
        return $var;
    }

    public function __toString() {
        return $this->q;
    }

}
