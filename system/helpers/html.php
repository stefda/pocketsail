<?php

function html($text) {
    $text = str_replace("\r\n", "</p><p>", $text);
    $text = "<p>" . str_replace("\n", "</p><p>", $text) . "</p>";
    $text = preg_replace("/\*\*([^*]*)\*\*/", "<h2>\\1</h2>", $text);
    $text = preg_replace("/\[([^|]*)\|([^\]]*)\]/", "<a href=\"\\2\" class=\"l\">\\1</a> <a href=\"\\2\"><img src=\"/application/images/open_in_new_window_small.png\" /></a>", $text);
    return $text;
}

function html_info($text) {
    $text = preg_replace("/\[([^|]*)\|([^\]]*)\]/", "<a href=\"\#\2\" class=\"l\">\\1</a> <a href=\"\\2\">", $text);
    return $text;
}
