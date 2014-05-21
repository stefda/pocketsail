<?php

class MySQLException extends Exception {
    
    public function __construct($message, $errno) {
        parent::__construct($message, $errno, NULL);
    }
}
