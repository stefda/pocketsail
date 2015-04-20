<?php

function array_remove_empty($array) {
    
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = array_remove_empty($array[$key]);
        }
        if (empty($array[$key])) {
            unset($array[$key]);
        }
    }
    return $array;
}

/**
 * @return CL_Database
 */
function get_mysql() {
    return CL_MySQL::get_instance();
}

/**
 * @return CL_Database
 */
function get_pgsql() {
    return CL_PgSQL::get_instance();
}

/**
 * @return CL_Mongo
 */
function get_mongo() {
    return CL_Mongo::get_instance();
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

/**
 * View helper functions
 */
function view_exists($view) {
    $viewPath = APPPATH . 'views/' . $view . '.php';
    return file_exists($viewPath);
}

function view_get_html($view) {
    return CL_Loader::get_instance()->view($view, FALSE);
}

/**
 * Config helpe functions
 */
function get_config_value($file, $key) {
    return CL_Config::get_instance()->get_value($file, $key);
}

/**
 * Flow controll functions.
 */
function error($o) {

    $message = "";
    $trace = NULL;

    if (is_a($o, "Exception")) {
        $message = $o->getMessage();
        $trace = $o->getTrace();
    } else {
        $message = $o;
        $trace = $trace = debug_backtrace();
    }
    handle_error($message, $trace);
}

function handle_error($message, $trace) {

    log_message($message . " " . json_encode($trace));

    if (DEBUG) {
        show_error($message, 'error', $trace);
    } else {
        location('/oops');
    }
    exit(-1);
}

function show_error($message, $type, $trace) {

    $details = [];

//    $max = 2;
    foreach ($trace AS $record) {
        array_push($details, $record);
//        if (--$max == 0) break;
    }

    if (key_exists('ajax', $_GET)) {
        show_ajax_error($message, $type, $details);
    } else {
        show_html_error($message, $type, $details);
    }
}

function show_html_error($message, $type, $details) {

    ob_start();
    include APPPATH . 'views/error/error.php';
    $buffer = ob_get_contents();
    ob_clean();

    header("HTTP/1.0 404 Not Found");
    echo $buffer;
}

function show_ajax_error($message, $type, $details) {

    header('Content-type: application/json');

    echo json_encode(array(
        'type' => $type,
        'value' => [
            'message' => strip_tags($message),
            'details' => $details
        ]
    ));
}

function log_message($message) {
    $log = CL_Log::get_instance();
    $log->write($message);
}

function error_handler($errno, $errstr, $errfile, $errline) {
    if (error_reporting() == 0) {
        return;
    }
    show_error("$errstr in $errfile on line $errline.", 'error', debug_backtrace());
}

function exception_handler(Exception $e) {
    show_error($e->getMessage(), 'uncaught_exception', $e->getTrace());
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
