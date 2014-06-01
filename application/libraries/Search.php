<?php

class Search {

    private $solr;
    private $keywords;

    public function __construct($solr, $keywords) {
        $this->solr = $solr;
        $this->keywords = $keywords;
    }

    public function do_search($term) {

        $items = [];
        $term = trim($term);
        $term = strtolower($term);

        // Do full type search
        if (($res = $this->check_full_type($term)) !== FALSE) {
            $poiBrief = NULL;
            $types = $res["types"];
            $label = $res["keyword"];
            $items[] = new SearchResult($poiBrief, $types, $label);
        }

        // Do partial type search
        if (($res = $this->check_partial_type($term)) !== FALSE) {
            $solrRes = $this->solr->query($res["stub"], 3);
            if ($solrRes->num_found() > 0) {
                $docs = $solrRes->docs();
                foreach ($docs AS $doc) {
                    $poiBrief = new POIBrief($doc);
                    $types = $res['types'];
                    $label = $res["keyword"] . " near " . $doc->name;
                    $items[] = new SearchResult($poiBrief, $types, $label);
                }
            }
        }

        // Search the full term just in case...
        $solrRes = $this->solr->query($term, 3);
        if ($solrRes->num_found() > 0) {
            $docs = $solrRes->docs();
            foreach ($docs AS $doc) {
                $poiBrief = new POIBrief($doc);
                $types = NULL;
                $label = $doc->name;
                $items[] = new SearchResult($poiBrief, $types, $label);
            }
        }

        return $items;
    }

    public function check_full_type($term) {
        foreach ($this->keywords AS $keyword => $types) {
            if (stripos($keyword, $term) === 0) {
                return [
                    "keyword" => $keyword,
                    "types" => $types,
                ];
            }
        }
        return FALSE;
    }

    public function check_partial_type($term) {
        $res = FALSE;
        // Check beginning
        foreach ($this->keywords AS $keyword => $types) {
            $count = 0;
            $pattern = "/^$keyword /";
            $stub = preg_replace($pattern, "", $term, 1, $count);
            if ($count > 0) {
                $res = [
                    "keyword" => $keyword,
                    "types" => $types,
                    "stub" => $stub
                ];
            }
        }
        if ($res !== FALSE) {
            return $res;
        }
        // Check end
        foreach ($this->keywords AS $keyword => $types) {
            $count = 0;
            $pattern = "/ $keyword$/";
            $stub = preg_replace($pattern, "", $term, 1, $count);
            if ($count > 0) {
                $res = [
                    "keyword" => $keyword,
                    "types" => $types,
                    "stub" => $stub
                ];
            }
        }
        return $res;
    }

}

class SearchResult implements JsonSerializable {

    private $poi;
    private $types;
    private $label;

    public function __construct($poi, $types, $label) {
        $this->poi = $poi;
        $this->types = $types;
        $this->label = $label;
    }

    public function jsonSerialize() {
        return [
            'poi' => $this->poi,
            'types' => $this->types,
            'label' => $this->label
        ];
    }

}

class POIBrief implements JsonSerializable {

    private $doc;

    public function __construct($doc) {
        $this->doc = $doc;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->doc->id,
            'name' => $this->doc->name,
            'subName' => $this->doc->subName,
            'nearName' => @$this->doc->nearName,
            'countryName' => @$this->doc->countryName
        ];
    }

}
