<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

function t($name, $info, $type = 'view') {
    assign_var('type', $type);
    assign_var('info', $info);
    return get_view_html('/template/' . $name);
}

function info_remove_empty($info) {
    foreach ($info as $key => $value) {
        if (is_array($value)) {
            $info[$key] = info_remove_empty($info[$key]);
        }

        if (info_is_empty($info[$key])) {
            unset($info[$key]);
        }
    }
    return $info;
}

function info_is_empty($val) {
    return $val !== 0 && $val !== '0' && (empty($val) || $val === 'nk');
}

function short_url($url) {
    $url = trim($url, '/');
    if (!preg_match('#^http(s)?://#', $url)) {
        $url = 'http://' . $url;
    }
    $urlParts = parse_url($url);
    $domain = preg_replace('/^www\./', '', $urlParts['host']);
    return $domain;
}

function full_url($url) {
    $url = trim($url, '/');
    if (!preg_match('#^http(s)?://#', $url)) {
        $url = 'http://' . $url;
    }
    return $url;
}