<?php

/**
 * Array helper functions
 */
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
    
    // Reassign array keys
    $res = array_values($res);

    $array = $res;
}

/**
 * Database helper functions
 */
function get_mysql() {
    return CL_MySQL::get_instance();
}

function get_mysqli() {
    return CL_MySQLi::get_instance();
}

function require_library($library) {

    // Enable including all libraries in a folder
    if (substr($library, -1) === '*') {
        $dirPath = APPPATH . 'libraries/' . rtrim($library, '*');
        $dp = opendir($dirPath);
        while ($file = readdir($dp)) {
            if ($file !== '.' && $file !== '..') {
                require_once $dirPath . $file;
            }
        }
        return;
    }

    if (!file_exists(APPPATH . 'libraries/' . $library . '.php')) {
        error("Requested library $library does not exist.");
    }
    require_once APPPATH . 'libraries/' . $library . '.php';
}

function require_model($model) {

    if (!file_exists(APPPATH . 'models/' . $model . '.php')) {
        error("Requested model $model does not exist.");
    }

    require_once APPPATH . 'models/' . $model . '.php';
}

/**
 * Config helper functions
 */
function &get_config($file = 'main') {

    static $configs = array();

    if (isset($configs[$file])) {
        return $configs[$file];
    }

    if (!file_exists(APPPATH . '/config/' . $file . '.php')) {
        error("The configuration file $file.php does not exits.");
    }

    require_once APPPATH . '/config/' . $file . '.php';

    if (!isset($config) OR ! is_array($config)) {
        error("The configuration file $file.php does not appear to be formatted correctly.");
    }

    $configs[$file] = & $config;
    return $configs[$file];
}

function get_config_value($file, $key) {
    return CL_Config::get_instance()->get_value($file, $key);
}

/**
 * Template helper functions
 */
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

    $subPath = APPPATH . 'views/templates/infobox/' . $sub . '.php';
    $catPath = APPPATH . 'views/templates/infobox/' . $cat . '.php';

    if (file_exists($subPath)) {
        return CL_Loader::get_instance()->view('templates/infobox/' . $sub, FALSE);
    } else if (file_exists($catPath)) {
        return CL_Loader::get_instance()->view('templates/infobox/' . $cat, FALSE);
    }

    // If neither sub- or cat-specific template exists, use default
    return CL_Loader::get_instance()->view('templates/infobox/default', FALSE);
}

function include_view_template($sub, $cat) {

    $subPath = APPPATH . 'views/templates/infopage/' . $sub . '.php';
    $catPath = APPPATH . 'views/templates/infopage/' . $cat . '.php';

    if (file_exists($subPath)) {
        return CL_Loader::get_instance()->view('templates/infopage/' . $sub, FALSE);
    } else if (file_exists($catPath)) {
        return CL_Loader::get_instance()->view('templates/infopage/' . $cat, FALSE);
    }

    // If neither sub- or cat-specific template exists, use default
    return CL_Loader::get_instance()->view('templates/infopage/default', FALSE);

//    $viewPath = APPPATH . 'views/templates/infopage/' . $sub . '.php';
//    if (file_exists($viewPath)) {
//        echo CL_Loader::get_instance()->view('templates/infopage/' . $sub, FALSE);
//        return;
//    }
//    echo CL_Loader::get_instance()->view('templates/infopage/' . $cat, FALSE);
}

/**
 * Error controll functions.
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
        show_error($message, $trace);
    } else {
        location('/oops');
    }
    exit(-1);
}

function show_error($message, $trace) {

    $get = filter_input_array(INPUT_GET);
    $details = [];

    foreach ($trace AS $record) {
        array_push($details, $record);
    }

    if ($get !== NULL && key_exists('ajax', $get)) {
        show_ajax_error($message, $details);
    } else {
        show_html_error($message, $details);
    }
}

function show_html_error($message, $details) {

    ob_start();
    include APPPATH . 'views/error/error.php';
    $buffer = ob_get_contents();
    ob_clean();

    header("HTTP/1.0 404 Not Found");
    echo $buffer;
    exit();
}

function show_ajax_error($message, $details) {

    header('Content-type: application/json');

    echo json_encode(array(
        'status' => 'error',
        'message' => strip_tags($message),
        'details' => $details
    ));
    exit();
}

function log_message($message) {
    $log = CL_Log::get_instance();
    $log->write($message);
}

function error_handler($errno, $errstr, $errfile, $errline) {
    if (error_reporting() == 0) {
        return;
    }
    show_error("$errstr in $errfile on line $errline ($errno).", debug_backtrace());
}

function exception_handler(Exception $e) {
    show_error($e->getMessage(), $e->getTrace());
}

function return_json($value) {
    header('Content-type: application/json');
    echo json_encode([
        'type' => 'return',
        'data' => [
            'type' => gettype($value),
            'value' => $value
        ]
    ]);
    exit();
}

/**
 * Flow control functions.
 */
function location($uri) {
    header('Location: ' . $uri);
    exit();
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

/**
 * Serialization functions
 */
function deserialize_input($type, $args) {
    $data = [];
    switch ($type) {
        case INPUT_POST: {
                $data = $_POST;
                break;
            }
        case INPUT_GET: {
                $data = $_GET;
                break;
            }
    }
    return deserialize($data, $args);
}

function deserialize($data, $args) {
    $res = [];
    foreach ($args AS $key => $value) {
        if (!isset($data[$key])) {
            continue;
        } else if (is_string($value)) {
            $res[$key] = call_user_func([$value, 'deserialize'], $data[$key]);
        } else if (is_int($value)) {
            $res[$key] = filter_var($data[$key], $value, FILTER_NULL_ON_FAILURE);
        } else if (is_array($value)) {
            $filter = isset($value['filter']) ? $value['filter'] : FILTER_DEFAULT;
            (isset($value['flags']) && $options = $value['flags'] | FILTER_NULL_ON_FAILURE) || $options = FILTER_NULL_ON_FAILURE;
            $res[$key] = filter_var($data[$key], $filter, $options);
//            if (isset($value['flags'])) {
//                $res[$key] = filter_var($data[$key], $filter, $value['flags'] | FILTER_NULL_ON_FAILURE);
//            } else {
//                $res[$key] = filter_var($data[$key], $filter, ['flags' => FILTER_NULL_ON_FAILURE]);
//            }
        }
    }
    return $res;
}
