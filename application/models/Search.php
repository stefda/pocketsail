<?php

class Search {

    private static $types = [
        "berthing" => ["berthing"],
        "marina" => ["marina", "marinas"],
        "anchorage" => ["anchorage", "anchorages"],
        "shop" => ["shop", "shops"],
        "gasstation" => ["gas station", "gas stations"],
        "restaurant" => ["restaurant", "restaurants"],
        "bar" => ["bar", "bars"],
        "cove" => ["cove", "coves"],
    ];
    private static $allowedTypes;
    private static $reverseTypes;
    private $pim;

    public function __construct($pim) {
        $this->pim = $pim;
        $this->build_type_terms();
    }

    private function build_type_terms() {
        $itr = new RecursiveIteratorIterator(new RecursiveArrayIterator(self::$types));
        foreach ($itr as $v) {
            self::$allowedTypes[] = $v;
        }
    }

    public function build_item($action, $value, $poi, $types) {
        $item = new stdClass();
        $item->action = $action;
        $item->value = $value;
        $item->poi = $poi;
        $item->types = $types;
        return $item;
    }

    public function search($term) {
        $term = trim($term);
        $items = [];
        if (($res = $this->check_type($term)) !== FALSE) {
            $items[] = $this->build_types_result($res->types, $res->value);
        } else if (($res = $this->find_types($term)) !== FALSE) {
            $items = array_merge($items, $this->build_near_result($res->types, $res->value, $res->rest));
        }
        $items = array_merge($items, $this->build_poi_result($term));
        return $items;
    }

    public function build_types_result($types, $value) {
        $item = new stdClass();
        $item->value = rtrim($value, 's') . 's';
        $item->types = $types;
        $item->action = 'types';
        return $item;
    }

    public function build_near_result($types, $value, $rest) {
        $items = [];
        if ($rest !== '') {
            $solrRes = $this->pim->search($rest, 0, 1);
            for ($i = 0; $i < $solrRes->count; $i++) {
                $solrItem = $solrRes->items[$i];
                $action = 'near_point';
                if (property_exists($solrItem, 'n')) {
                    $action = 'near_bounds';
                }
                $items[] = $this->build_item($action, $value . ' near ' . $solrItem->name, $solrItem, $types);
            }
        }
        if (count($items) === 0) {
            $items[] = $this->build_types_result($types, $value);
        }
        return $items;
    }

    public function build_poi_result($term) {
        $items = [];
        $solrRes = $this->pim->search($term);
        for ($i = 0; $i < $solrRes->count; $i++) {
            $solrItem = $solrRes->items[$i];
            $action = 'point';
            if (property_exists($solrItem, 'n')) {
                $action = 'bounds';
            }
            $items[] = $this->build_item($action, $solrItem->name, $solrItem, []);
        }
        return $items;
    }

    public function check_type($term) {
        foreach (self::$types AS $type => $allowedTerms) {
            foreach ($allowedTerms AS $allowedTerm) {
                if (stripos($allowedTerm, $term) !== false) {
                    $res = new stdClass();
                    $res->value = $allowedTerm;
                    $res->types = [$type];
                    return $res;
                }
            }
        }
        return FALSE;
    }

    public function find_types($term) {
        $types = [];
        $values = [];
        foreach (self::$types AS $type => $allowedTerms) {
            foreach ($allowedTerms AS $allowedTerm) {
                $count = 0;
                $pattern = '/(^|[ ,])' . $allowedTerm . '([ ,])/i';
                $term = preg_replace($pattern, '', $term, 1, $count);
                if ($count > 0) {
                    $types[] = $type;
                    $values[] = rtrim($allowedTerm, 's') . 's';
                }
            }
        }
        $term = trim($term);
        foreach (self::$types AS $type => $allowedTerms) {
            foreach ($allowedTerms AS $allowedTerm) {
                if ($term === $allowedTerm) {
                    $types[] = $type;
                    $values[] = rtrim($allowedTerm, 's') . 's';
                    $term = '';
                }
            }
        }
        if (count($types) > 0) {
            $res = new stdClass();
            $res->types = $types;
            $res->value = implode($values, ', ');
            $res->rest = $term;
            return $res;
        }
        return FALSE;
    }

}
