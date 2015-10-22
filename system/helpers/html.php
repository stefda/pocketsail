<?php

function html($text) {
    $text = str_replace("\r\n", "</p><p>", $text);
    $text = "<p>" . str_replace("\n", "</p><p>", $text) . "</p>";
    $text = preg_replace("/\*\*([^*]*)\*\*/", "<h2>\\1</h2>", $text);
    $text = preg_replace("/\[([^|]*)\|([^\]]*)\]/", "<a href=\"\\2\" class=\"l\">\\1</a> <a href=\"\\2\"><img src=\"/application/images/open_in_new_window_small.png\" /></a>", $text);
    return $text;
}

function html_info($text) {
    $text = preg_replace("/\[([^|]*)\|([^\]]*)\]/", "\\1", $text);
    return $text;
}

function solution($A) {
    
    $N = count($A);
    $M = count($A[0]);
    
    $P = [0];
    $Q = [0];
    $S = [$A[0][0]];
    
    $max = $A[0][0];
    
    while (count($P) > 0) {
        
        $p = array_shift($P);
        $q = array_shift($Q);
        $s = array_shift($S);
        
        if ($p + 1 != $N) {
            $P[] = $p + 1;
            $Q[] = $q;
            $S[] = $s + $A[$p + 1][$q];
        }
        
        if ($q + 1 != $M) {
            $P[] = $p;
            $Q[] = $q + 1;
            $S[] = $s + $A[$p][$q + 1];
        }
    }
}
