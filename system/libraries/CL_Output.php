<?php

if (!defined('SYSPATH')) exit("No direct script access allowed!");

class CL_Output {

    private static $instance = NULL;

    public $buffer;
    public $vars = array();

    private function CL_Output() {
    }

    /**
     * @return CL_Output
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Output();
        }
        return self::$instance;
    }

    public function append($buffer) {
        $this->buffer .= $buffer;
    }

    public function assign($var, $value) {
        $this->assign_vars(array($var => $value));
    }

    public function assign_vars($vars) {
        foreach ($vars as $var => $value) {
            $this->vars[$var] = $value;
        }
    }

    public function assign_js($vars) {
        $html = "\n\n<script type=\"text/javascript\">\n";
        foreach ($vars as $var => $value) {
            if (!is_string($value) && !is_numeric($value)) continue;
            if (is_string($value)) $value = "'" . $value . "'";
            $html .= "    var " . $var . " = " . $value . ";\n";
        }
        $html .= "</script>\n";
        $this->append($html);
    }

    public function display() {
        echo $this->buffer;
    }
}


/* End of file CL_Output.php */
/* Location: /system/libraries/CL_Output.php */