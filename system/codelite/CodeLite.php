<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

/**
 * Define autoload function to load the CL core libraries
 */
function __autoload($name) {
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
    require_once SYSPATH . 'libraries/' . $name . '.php';
}

/**
 * Load the Common functions file
 */
require_once SYSPATH . 'codelite/Common.php';

set_error_handler('error_handler');

/* * ***************************************************************
 * Load some classes
 */
$router = CL_Router::get_instance();
$out = CL_Output::get_instance();

/* * ***************************************************************
 * Retrieve class and method
 */
$class = $router->fetch_class();
$method = $router->fetch_method();
$parameters = $router->fetch_parameters();

if (!controller_exists($class)) {
    require_once APPPATH . 'controllers/poi.php';
    $controller = new POI();
    $res = $controller->view($class);
    $controller->end();
//    header('HTTP/1.0 404 Not Found');
//    error("Controller $class not found.");
} else {
    require_once APPPATH . 'controllers/' . $class . '.php';
    if (!controller_is_callable($class, $method)) {
        header('HTTP/1.0 404 Not Found');
        error("Requested controller $class/$method is not callable. Does the method <b>$method</b> exist?");
    }
    $controller = new $class();
    $res = call_user_func_array(array($controller, $method), $parameters);
    $controller->end();
}

$get = filter_input_array(INPUT_GET);

if ($get !== NULL && isset($get['ajax'])) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => 'OK',
        'value' => $res
    ));
    exit();
}

$out->display();

exit();
