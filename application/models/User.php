<?php

class User {
    
    private $id;
    private $login;
    private $email;
    private $fname;
    private $lname;
    private $role;
    private $timestamp;
    
    public function __construct($o) {
        $this->id = $o->id;
        $this->login = $o->login;
        $this->email = $o->email;
        $this->fname = $o->fname;
        $this->lname = $o->lname;
        $this->timestamp = $o->timestamp;
    }
    
    public static function load($id) {
        
        $mysql = CL_MySQL::getInstance();
        
        $mysql->select('*', 'user', [
            'AND' => [
                'id' => $id
            ]
        ]);
    }
}
