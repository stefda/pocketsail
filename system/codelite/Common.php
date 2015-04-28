<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
}

function aasort(&$array, $key) {
    $res = array();
    $sorted = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorted[$ii] = $va[$key];
    }
    asort($sorted);
    foreach ($sorted as $ii => $va) {
        $res[$ii] = $array[$ii];
    }
    $array = $res;
}

function db() {
    return CL_MySQLi::get_instance();
}

function require_library($library) {
    if (!file_exists(APPPATH . 'libraries/' . $library . '.php')) {
        show_error("Requested library does not exist.", "Loader Error");
    }
    require_once APPPATH . 'libraries/' . $library . '.php';
}

function &get_config($file = 'main') {

    static $configs = array();

    if (isset($configs[$file])) {
        return $configs[$file];
    }

    if (!file_exists(APPPATH . '/config/' . $file . '.php')) {
        show_error("The configuration file $file.php does not exits.", "Config Error");
    }

    require_once APPPATH . '/config/' . $file . '.php';

    if (!isset($config) OR ! is_array($config)) {
        show_error("The configuration file $file.php does not appear to be formatted correctly.", "Config Error");
    }

    $configs[$file] = & $config;
    return $configs[$file];
}

function controller_exists($class) {

    if (!file_exists(APPPATH . 'controllers/' . $class . '.php')) {
        return false;
    }

    return true;
}

function controller_is_callable($class, $method) {

    if (!method_exists($class, $method)) {
        return false;
    }

    return true;
}

function view_exists($view) {
    $viewPath = APPPATH . 'views/' . $view . '.php';
    return file_exists($viewPath);
}

function view_get_html($view) {
    return CL_Loader::get_instance()->view($view, FALSE);
}

//
// TO BE REMOVED
//
function include_view($view) {
    return CL_Loader::get_instance()->view($view, FALSE);
}

function include_edit_template($cat, $sub) {

    $subPath = APPPATH . 'views/templates/edit/' . $sub . '.php';
    $catPath = APPPATH . 'views/templates/edit/' . $cat . '.php';

    if (file_exists($subPath)) {
        return CL_Loader::get_instance()->view('templates/edit/' . $sub, FALSE);
    } else if (file_exists($catPath)) {
        return CL_Loader::get_instance()->view('templates/edit/' . $cat, FALSE);
    }

    // If neither sub- or cat-specific template exists, use default
    return CL_Loader::get_instance()->view('templates/edit/default', FALSE);
}

function include_card_template($cat, $sub) {

    $subPath = APPPATH . 'views/templates/card/' . $sub . '.php';
    $catPath = APPPATH . 'views/templates/card/' . $cat . '.php';

    if (file_exists($subPath)) {
        return CL_Loader::get_instance()->view('templates/card/' . $sub, FALSE);
    } else if (file_exists($catPath)) {
        return CL_Loader::get_instance()->view('templates/card/' . $cat, FALSE);
    }

    // If neither sub- or cat-specific template exists, use default
    return CL_Loader::get_instance()->view('templates/card/default', FALSE);
}

function include_view_template($sub, $cat) {
    $viewPath = APPPATH . 'views/templates/view/' . $sub . '.php';
    if (file_exists($viewPath)) {
        echo CL_Loader::get_instance()->view('templates/view/' . $sub, FALSE);
        return;
    }
    echo CL_Loader::get_instance()->view('templates/view/' . $cat, FALSE);
}

//
// TO BE REMOVED UNTIL HERE
//

function throwErrorAndExit($message, $header = 'error', $type = 'default') {

    if ($header == 'error') {
        $trace = debug_backtrace();
        if (count($trace) > 1 && key_exists('function', $trace[1]) && key_exists('class', $trace[1])) {
            $header = $trace[1]['class'] . '::' . $trace[1]['function'];
        }
    }

    $err = CL_Error::get_instance();

    if (key_exists('ajax', $_GET)) {
        echo $err->show_ajax_error($message, $type);
    } else {
        echo $err->show_error($message, $header, $type);
    }

    log_message($message);
    exit(-1);
}

function show_error($message, $header = 'error', $type = 'default') {

    if ($header == 'error') {
        $trace = debug_backtrace();
        if (count($trace) > 1 && key_exists('function', $trace[1]) && key_exists('class', $trace[1])) {
            $header = $trace[1]['class'] . '::' . $trace[1]['function'];
        }
    }

    $err = CL_Error::get_instance();

    if (key_exists('ajax', $_GET)) {
        echo $err->show_ajax_error($message, $type);
    } else {
        echo $err->show_error($message, $header, $type);
    }

    log_message($message);
    exit(-1);
}

function log_message($message) {
    $log = CL_Log::get_instance();
    $log->write($message);
}

function error_handler($errno, $errstr, $errfile, $errline) {
    if (error_reporting() == 0) {
        return;
    }
    show_error("$errstr in $errfile on line $errline.", "PHP Error");
}

function return_json($value) {
    header('Content-type: application/json');
    echo json_encode(array('type' => 'return', 'data' => array('type' => gettype($value), 'value' => $value)));
    exit();
}

function location($uri) {
    header('Location: ' . $uri);
    exit();
}
