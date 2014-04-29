<?php

class Template {
    
    public function __construct($info, $inc, $exc) {
        $this->info = $info;
        $this->inc = $inc;
        $this->exc = $inc;
    }
    
    public function get_view_html() {
        $html = '';
        foreach (@$this->info AS $class => $info) {
            assign_var('t', $info);
            assign_var('inc', @$this->inc->{$class});
            assign_var('exc', @$this->inc->{$class});
            $html .= get_view_html('templates/view/' . $class);
        }
        return $html;
    }
    
    public function has_paragraph($paragraph) {
        return property_exists($this->info, $paragraph);
    }
}