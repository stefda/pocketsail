<?php

class CL_Exception extends Exception {

    protected $message;

    public function CL_Exception($message = '') {
        $this->message = $message;
    }

}
