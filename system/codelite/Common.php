<?php

if (!defined('SYSPATH')) {
    exit("No direct script access allowed!");
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

function pl($str) {
    echo $str;
    br();
}

function br() {
    echo "<br />";
}

function printJS($o) {
    if ($o === NULL) {
        return 'null';
    }
    if ($o === FALSE) {
        return 'false';
    }
    if ($o === TRUE) {
        return 'true';
    }
    if (gettype($o) === 'string') {
        return "'$o'";
    }
    if (is_numeric($o)) {
        return $o;
    }
    if (method_exists($o, 'jsonSerialize')) {
        return json_encode($o->jsonSerialize());
    }
    return 'NULL';
}

/**
 * Return all values of an array.
 * 
 * @param array $array Tha input array.
 * @return array An indexed array of values.
 */
function array_values_recursive($array) {
    $values = [];
    array_walk_recursive($array, function ($value) use(&$values) {
        $values[] = $value;
    }, $values);
    return $values;
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

    if (!isset($config) OR !is_array($config)) {
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

function get_base_url() {
    return BASEURL;
}

function get_full_url($path = '') {
    $url = BASEURL . $path;
    return $url;
}

function assign_var($var, $value) {
    CL_Output::get_instance()->assign($var, $value);
}

function load_view($view) {
    CL_Loader::get_instance()->view($view);
}

function get_view_html($view) {
    return CL_Loader::get_instance()->view($view, FALSE);
}

function tpl_assign_var($name, $value) {
    CL_Output::get_instance()->assign($name, $value);
}

function tpl_get_html($name, $type) {
    assign_var('type', $type);
    return CL_Loader::get_instance()->view('template/' . $name, FALSE);
}

function tpl_include($name, $info = NULL) {
    if ($info !== NULL) {
        assign_var('info', $info);
    }
    echo CL_Loader::get_instance()->view('template/' . $name, FALSE);
}

function include_view($view) {
    echo CL_Loader::get_instance()->view($view, FALSE);
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

function objects_to_arrays($array) {
    $arrays = array();
    foreach ($array as $element) {
        array_push($arrays, $element->__toArray());
    }
    return $arrays;
}

function return_json($value) {
    header('Content-type: application/json');
    echo json_encode(array('type' => 'return', 'data' => array('type' => gettype($value), 'value' => $value)));
    exit();
}

function return_iframe($value) {
    echo json_encode(array('type' => 'return', 'data' => array('type' => gettype($value), 'value' => $value)));
    exit();
}

function return_html($value) {
    header('Content-type: text/html');
    echo json_encode(array('type' => 'return', 'data' => array('type' => gettype($value), 'value' => $value)));
    exit();
}

function location($uri) {
    header('Location: ' . $uri);
    exit();
}

function to_js_array($val) {
    if (is_numeric($val))
        return $val;
    if (is_string($val))
        return '"' . $val . '"';
    if (is_bool($val))
        return $var ? 'true' : 'false';
    if (is_null($val))
        return 'NULL';
    if (is_object($val))
        return 'N/A';
    $jsArray = '[';
    $count = count($val);
    for ($i = 0; $i < $count; $i++) {
        $jsArray .= to_js_array($val[$i]);
        $jsArray .= $i < $count - 1 ? ', ' : '';
    }
    return $jsArray . ']';
}

function post_keys_exist() {
    $args = func_get_args();
    foreach ($args AS $arg) {
        if (!key_exists($arg, $_POST)) {
            return FALSE;
        }
    }
    return TRUE;
}

function file_keys_exist() {
    $args = func_get_args();
    foreach ($args AS $arg) {
        if (!key_exists($arg, $_FILES)) {
            return FALSE;
        }
    }
    return TRUE;
}

function get_keys_exist() {
    $args = func_get_args();
    foreach ($args AS $arg) {
        if (!key_exists($arg, $_GET)) {
            return FALSE;
        }
    }
    return TRUE;
}

function is_mobile() {

    $isMobile = false;
    $op = key_exists('HTTP_X_OPERAMINI_PHONE', $_SERVER) ? strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']) : '';
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);

    return strpos($ac, 'application/vnd.wap.xhtml+xml') !== false || $op != '' || strpos($ua, 'sony') !== false || strpos($ua, 'symbian') !== false || strpos($ua, 'nokia') !== false || strpos($ua, 'samsung') !== false || strpos($ua, 'mobile') !== false || strpos($ua, 'windows ce') !== false || strpos($ua, 'epoc') !== false || strpos($ua, 'opera mini') !== false || strpos($ua, 'nitro') !== false || strpos($ua, 'j2me') !== false || strpos($ua, 'midp-') !== false || strpos($ua, 'cldc-') !== false || strpos($ua, 'netfront') !== false || strpos($ua, 'mot') !== false || strpos($ua, 'up.browser') !== false || strpos($ua, 'up.link') !== false || strpos($ua, 'audiovox') !== false || strpos($ua, 'blackberry') !== false || strpos($ua, 'ericsson,') !== false || strpos($ua, 'panasonic') !== false || strpos($ua, 'philips') !== false || strpos($ua, 'sanyo') !== false || strpos($ua, 'sharp') !== false || strpos($ua, 'sie-') !== false || strpos($ua, 'portalmmm') !== false || strpos($ua, 'blazer') !== false || strpos($ua, 'avantgo') !== false || strpos($ua, 'danger') !== false || strpos($ua, 'palm') !== false || strpos($ua, 'series60') !== false || strpos($ua, 'palmsource') !== false || strpos($ua, 'pocketpc') !== false || strpos($ua, 'smartphone') !== false || strpos($ua, 'rover') !== false || strpos($ua, 'ipaq') !== false || strpos($ua, 'au-mic,') !== false || strpos($ua, 'alcatel') !== false || strpos($ua, 'ericy') !== false || strpos($ua, 'up.link') !== false || strpos($ua, 'vodafone/') !== false || strpos($ua, 'wap1.') !== false || strpos($ua, 'wap2.') !== false;
}
