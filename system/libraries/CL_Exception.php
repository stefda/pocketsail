<?php

if (!defined('SYSPATH')) exit("No direct script access allowed!");

class CL_Exception extends Exception {

    public function CL_Exception($message = '', $code = 0) {
        $this->message = $message;
        $this->code = $code;
        log_message($this->message);
    }

    public function get_message() {
        return $this->message;
    }
}


/* End of file CL_Exception.php */
/* Location: /system/libraries/CL_Exception.php */