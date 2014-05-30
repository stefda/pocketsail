<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class Compile extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {

        $fldrs = array();
        $appPath = '';

        if (key_exists('app', $_GET)) {
            $fldrs[0] = array(
                APPPATH . '/apps/' . $_GET['app'] . '/controllers',
                APPPATH . '/apps/' . $_GET['app'] . '/js/controllers'
            );
            $appPath = 'app/' . $_GET['app'] . '/';
        } else {
            $fldrs[0] = array(
                APPPATH . 'controllers',
                APPPATH . 'js/controllers'
            );
        }

        if (!file_exists($fldrs[0][1])) {
            mkdir($fldrs[0][1], 0777, TRUE);
        }

        $dh = opendir($fldrs[0][1]);
        while ($fileName = readdir($dh)) {
            if ($fileName != '.' && $fileName != '..') {
                @unlink($fldrs[0][1] . '/' . $fileName);
            }
        }

        $ctrls = array();
        $dh = opendir($fldrs[0][0]);

        while ($fileName = readdir($dh)) {

            if (strlen($fileName) > 4) {
                $controller = substr($fileName, 0, strlen($fileName) - 4);
                include_once $fldrs[0][0] . '/' . $fileName;

                $rc = new ReflectionClass($controller);
                $methods = $rc->getMethods();
                $methodsArray = array();
                $className = $rc->getName();

                foreach ($methods as $method) {
                    $comment = $method->getDocComment();
                    $callableRegexp = '/@AjaxCallable/';

                    if (preg_match($callableRegexp, $comment)) {

                        $asyncRegexp = '/@AjaxAsync=(TRUE|FALSE)/';
                        $typeRegexp = '/@AjaxMethod=(GET|POST)/';

                        preg_match($asyncRegexp, $comment, $matches);
                        $async = (count($matches) > 1) ? $matches[1] : 'FALSE';

                        preg_match($typeRegexp, $comment, $matches);
                        $type = (count($matches) > 1) ? $matches[1] : 'GET';

                        $methods = array();

                        foreach ($method->getParameters() as $param) {
                            array_push($methods, $param->getName());
                        }

                        array_push($methodsArray, array(
                            'name' => $method->getName(),
                            'params' => $methods,
                            'type' => $type,
                            'async' => $async
                        ));
                    }
                }

                if (count($methodsArray) > 0) {
                    $ctrls[] = array(
                        'name' => $className,
                        'methods' => $methodsArray
                    );
                }
            }
        }

        foreach ($ctrls as $ctrl) {

            $fp = fopen($fldrs[0][1] . '/' . $ctrl['name'] . 'Broker.js', 'w');
            $ctrlName = $ctrl['name'];
            $methods = $ctrl['methods'];

            $code = "var {$ctrlName}Broker = function () {\n};\n\n";

            for ($i = 0; $i < count($methods); $i++) {

                $methodName = $methods[$i]['name'];
                $lcCrtlName = strtolower($ctrlName);

                $params = $methods[$i]['params'];
                $paramsExt = $params;

                foreach ($params as $key => $param) {
                    $params[$key] = "encodeURIComponent($param)";
                }

                $paramsExt[] = 'options';
                $paramsString = implode("+'/'+", $params);
                $paramsString = $paramsString == '' ? '' : "'+$paramsString+'/";
                $async = $methods[$i]['async'] == 'TRUE' ? 'true' : 'false';

                $code .= "{$ctrlName}Broker.$methodName = function(" . implode(', ', $paramsExt) . ") {\n";

                $code .= "var o = {};";
                //$code .= "o.url = '/" . $appPath . "$lcCrtlName/$methodName/$paramsString?ajax&route';\n";
                $code .= "o.url = '/broker/call/$lcCrtlName/$methodName/$paramsString?ajax&route';\n";
                $code .= "o.type = '" . $methods[$i]['type'] . "';\n";
                $code .= "o.async = $async;\n";
                $code .= "if (options !== undefined && options.exception !== undefined) {o.exception = options.exception;}\n";
                $code .= "if (options !== undefined && options.beforeSend !== undefined) {o.beforeSend = options.beforeSend;}\n";
                $code .= "if (options !== undefined && options.success !== undefined) {o.success = options.success;}\n";
                $code .= "if (options !== undefined && options.post !== undefined) {o.data = options.post;}\n";
                $code .= "return ajax(o);\n";
                $code .= "};\n\n";
            }

            fwrite($fp, $code);
            fclose($fp);

            echo '<span style="font-family: courier">Writing</span><i> ' . $ctrl['name'] . 'Broker.js</i><br />';
        }

        echo '<br />Done';
    }

}
