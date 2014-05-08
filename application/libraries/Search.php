<?php

class Search {

    private $keys;

    public function __construct($catKeywords, $subKeywords) {
        $this->keys = [
            "cat" => $catKeywords,
            "sub" => $subKeywords
        ];
    }

    public function do_search($term) {
        // Trim term
        $term = trim($term);
        // Do full type search
        if ($res = $this->check_full_type($term)) {
            echo "TYPE\n";
            print_r($res);
        }
        if ($res = $this->check_partial_type($term)) {
            echo "TYPE NEAR\n";
            print_r($res);
        }
        // Do partial type search
    }

    public function check_full_type($term) {
        foreach ($this->keys AS $kind => $keywords) {
            foreach ($keywords AS $keyword => $type) {
                if (stripos($keyword, $term) !== FALSE) {
                    return [
                        "keyword" => $keyword,
                        $kind => $type,
                    ];
                }
            }
        }
        return FALSE;
    }

    public function check_partial_type($term) {
        // Check beginning
        foreach ($this->keys AS $kind => $keywords) {
            foreach ($keywords AS $keyword => $type) {
                $count = 0;
                $pattern = "/^$keyword /";
                $newTerm = preg_replace($pattern, "", $term, 1, $count);
                if ($count > 0) {
                    return [
                        "keyword" => $keyword,
                        $kind => $type,
                        "term" => $newTerm
                    ];
                }
            }
        }
        // Check end
        foreach ($this->keys AS $kind => $keywords) {
            foreach ($keywords AS $keyword => $type) {
                $count = 0;
                $pattern = "/ $keyword$/";
                $newTerm = preg_replace($pattern, "", $term, 1, $count);
                if ($count > 0) {
                    return [
                        "keyword" => $keyword,
                        $kind => $type,
                        "term" => $newTerm
                    ];
                }
            }
        }
        return FALSE;
    }

}
