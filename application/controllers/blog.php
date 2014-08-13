<?php

class Blog extends CL_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function log($url, $id) {
        
        $mysql = CL_MySQL::getInstance();
        $mysql->insert('blog', [
            'url' => $url,
            'id' => $id
        ]);
    }
}
