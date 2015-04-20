<?php

/* * ***************************************************************
 * Define autoload function to load the CL core libraries
 */

function __autoload($name) {
    require_once SYSPATH . 'libraries/' . $name . '.php';
}

/* * ***************************************************************
 * Load the Common functions file
 */
require_once SYSPATH . 'codelite/Common.php';

set_error_handler('error_handler');
set_exception_handler('exception_handler');

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
    header('HTTP/1.0 404 Not Found');
    show_error("Controller $class not found.", 'Controller Error');
}

require_once APPPATH . 'controllers/' . $class . '.php';

if (!controller_is_callable($class, $method)) {
    header('HTTP/1.0 404 Not Found');
    show_error("Requested controller $class/$method is not callable. Does the method <b>$method</b> exist?", "Controller Error");
}

$controller = new $class();
call_user_func_array(array($controller, $method), $parameters);

$controller->end();
$out->display();

//echo "exec " . (microtime(TRUE) - $start);

exit();
