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
            $items[] = [
                "types" => $res["types"],
                "label" => $res["keyword"],
                "poi" => NULL
            ];
        }

        // Do partial type search
        if (($res = $this->check_partial_type($term)) !== FALSE) {
            $solrRes = $this->solr->query($res["stub"], 3);
            if ($solrRes->num_found() > 0) {
                $docs = $solrRes->docs();
                foreach ($docs AS $doc) {
                    $items[] = [
                        "types" => $res["types"],
                        "label" => $res["keyword"] . " near " . $doc->name,
                        "poi" => [
                            "id" => $doc->id,
                            "name" => $doc->name,
                            "subName" => $doc->subName,
                            "near" => @$doc->nearName,
                            "country" => @$doc->countryName
                        ]
                    ];
                }
            }
        }

        $solrRes = $this->solr->query($term, 3);
        if ($solrRes->num_found() > 0) {
            $docs = $solrRes->docs();
            foreach ($docs AS $doc) {
                $items[] = [
                    "types" => NULL,
                    "label" => $doc->name,
                    "poi" => [
                        "id" => $doc->id,
                        "name" => $doc->name,
                        "subName" => $doc->subName,
                        "near" => @$doc->nearName,
                        "country" => @$doc->countryName
                    ]
                ];
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
