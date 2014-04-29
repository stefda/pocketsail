<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class CL_Error {

    private static $instance = NULL;

    private function CL_Error() {
        
    }

    public function show_error($message, $heading, $type = 'default') {

        ob_start();
        include APPPATH . 'errors/' . $type . '_error.php';
        $buffer = ob_get_contents();
        ob_clean();

        return $buffer;
    }

    public function show_ajax_error($message, $type = 'default') {

        header('Content-type: application/json');

        echo json_encode(array(
            'type' => 'error',
            'data' => array(
                'errstr' => strip_tags($message),
                'errtype' => $type
            )
        ));
    }

    /**
     * @return CL_Error
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Error();
        }
        return self::$instance;
    }

}

/* End of file CL_Error.php */
/* Location: /system/libraries/CL_Error.php */