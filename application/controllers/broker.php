<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class Broker extends CL_Controller {

    function call($class, $method) {
        $parameters = CL_Router::get_instance()->fetch_parameters();
        if (!controller_exists($class)) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }
        require_once APPPATH . 'controllers/' . $class . '.php';
        $controller = new $class();
        try {
            $res = call_user_func_array(array($controller, $method), array_slice($parameters, 2));
        } catch (CL_Exception $e) {
            if (key_exists('ajax', $_GET)) {
                header('Content-type: application/json');
                echo json_encode(array(
                    'type' => 'error',
                    'data' => array(
                        'errstr' => strip_tags($e->get_message()),
                        'errtype' => ''
                    )
                ));
                exit();
            } else {
                throw new CL_Exception($e->get_message());
            }
        }
        if (key_exists('ajax', $_GET)) {
            return_json($res);
        } else {
            return $res;
        }
    }

    function ajax($class, $method) {
        $parameters = CL_Router::get_instance()->fetch_parameters();
        if (!controller_exists($class)) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }
        require_once APPPATH . 'controllers/' . $class . '.php';
        $controller = new $class();
        try {
            $res = call_user_func_array(array($controller, $method), array_slice($parameters, 2));
        } catch (CL_Exception $e) {
            header('Content-type: application/json');
            echo json_encode(array(
                'type' => 'error',
                'data' => array(
                    'errstr' => strip_tags($e->get_message()),
                    'errtype' => ''
                )
            ));
            exit();
        }
        return_json($res);
    }
    
    function iframe($class, $method) {
        $parameters = CL_Router::get_instance()->fetch_parameters();
        if (!controller_exists($class)) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }
        require_once APPPATH . 'controllers/' . $class . '.php';
        $controller = new $class();
        try {
            $res = call_user_func_array(array($controller, $method), array_slice($parameters, 2));
        } catch (CL_Exception $e) {
            echo json_encode(array(
                'type' => 'error',
                'data' => array(
                    'errstr' => strip_tags($e->get_message()),
                    'errtype' => ''
                )
            ));
            exit();
        }
        return_iframe($res);
    }

}

/* End of file broker.php */
/* Location: /application/controllers/broker.php */