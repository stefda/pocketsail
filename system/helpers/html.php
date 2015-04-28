<?php

function html($text) {
    $text = str_replace("\r\n", "</p><p>", $text);
    $text = "<p>" . str_replace("\n", "</p><p>", $text) . "</p>";
    $text = preg_replace("/\*\*([^*]*)\*\*/", "<h2>\\1</h2>", $text);
    $text = preg_replace("/\[([^|]*)\|([^\]]*)\]/", "<a href=\"\\2\">\\1</a>", $text);
    return $text;
}
