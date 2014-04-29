<?php

if (!defined('SYSPATH')) exit("No direct script access allowed!");

function url_request_create($reqArray) {

    $req = "";
    $counter = 0;

    foreach ($reqArray as $key => $value) {
        $key = trim($key);
        if (strlen($key) > 0) {
            $req .= ($counter > 0 ? "&" : "") . $key . "=" . $value;
            $counter++;
        }
    }

    return  $req;
}

/**
 * @param   string $url
 * @param   mixed $toAdd
 * @return  string
 */
function url_request_add($url, $toAdd) {

    if (is_array($toAdd)) {
        $toAdd = url_request_create($toAdd);
    }

    // trim whitespaces off the original url
    $url = trim($url);

    return $url . (strlen($url) > 0 ? "&" : "") . $toAdd;
}

function header_get_create($path, $req = "") {

    if ($path[0] == '/') {
        $path = substr($path, 1, strlen($path));
    }

    if (is_array($req)) {
        $req = url_request_create($req);
    }
    else {
        $req = trim($req);
    }

    if (strlen($req) > 0 && $req[0] != '?') {
        $req = "?$req";
    }

    $path .= $req;

    return "GET /$path HTTP/1.0\r\n\r\n";
}

function header_post_create($path, $req = "") {

    if ($path[0] == '/') {
        $path = substr($path, 1, strlen($path));
    }

    if (is_array($req)) {
        $req = url_request_create($req);
    }
    else {
        $req = trim($req);
    }

    $header =  "";
    $header .= "POST /$path HTTP/1.0\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-length: " . strlen($req) . "\r\n\r\n";

    return $header . $req;
}


/* End of file uri_helper.php */
/* Location: /system/helpers/uri_helper.php */