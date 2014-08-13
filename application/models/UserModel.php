<?php

class UserModel {

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
        $this->role = $o->role;
        $this->timestamp = $o->timestamp;
    }

    public static function loadById($id) {
        
        $mysql = CL_MySQL::getInstance();
        $mysql->select('user', '*', ['id' => $id]);
        $o = $mysql->fetchObject();
        
        if ($o === NULL) {
            return NULL;
        }
        
        return new UserModel($o);
    }

}
