<?php

if (!defined('SYSPATH')) exit("No direct script access allowed!");

class CL_Meta {
    
    private $level;
    private $type;
    private $value;
    
    public function __construct($level, $type, $value) {
        $this->level = $level;
        $this->type = $type;
        $this->value = $value;
    }
    
    public static function load($type, $locale, $sectionId, $pageId) {
        $type = mysql_real_escape_string($type);
        $locale = mysql_real_escape_string($locale);
        $mysql = CL_MySQL::get_instance();
        $r = $mysql->query("SELECT * FROM sb_meta WHERE type = '$type' AND value != '' AND (locale = '$locale' OR section_id = $sectionId OR page_id = $pageId) ORDER BY level DESC LIMIT 1");
        if ($mysql->num_rows($r) == 0) {
            return new CL_Meta(0, '', '');
        }
        $o = $mysql->fetch_object($r);
        return new CL_Meta($o->level, $o->type, $o->value);
    }
    
    public static function set_locale($type, $locale, $value) {
        $value = mysql_real_escape_string($value);
        $locale = mysql_real_escape_string($locale);
        $mysql = CL_MySQL::get_instance();
        $mysql->query("INSERT INTO sb_meta (locale, level, type, value) VALUES ('$locale', 1, '$type', '$value') ON DUPLICATE KEY UPDATE value = '$value'");
    }
    
    public static function set_section($type, $sectionId, $value) {
        $value = mysql_real_escape_string($value);
        $mysql = CL_MySQL::get_instance();
        $mysql->query("INSERT INTO sb_meta (section_id, level, type, value) VALUES ($sectionId, 2, '$type', '$value') ON DUPLICATE KEY UPDATE value = '$value'");
    }
    
    public static function set_page($type, $pageId, $value) {
        $value = mysql_real_escape_string($value);
        $mysql = CL_MySQL::get_instance();
        $mysql->query("INSERT INTO sb_meta (page_id, level, type, value) VALUES ($pageId, 3, '$type', '$value') ON DUPLICATE KEY UPDATE value = '$value'");
    }
    
    public function get_level() {
        return $this->level;
    }
    
    public function get_type() {
        return $this->type;
    }
    
    public function get_value() {
        return $this->value;
    }
}


/* End of file CL_Meta.php */
/* Location: /system/libraries/CL_Meta.php */