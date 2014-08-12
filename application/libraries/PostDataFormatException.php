<?php

/**
 * @author David Stefan
 */
class PostDataFormatException {
    
    public function __construct($message) {
        echo $message;
        exit();
    }
}
