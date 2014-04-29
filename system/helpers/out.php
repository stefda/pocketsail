<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

function print_line($line) {
    echo $line . '<br />';
}

function print_a($a) {
    echo nl2br(str_replace(' ', '&nbsp;', print_r($a, TRUE)));
}

/* End of file out.php */
/* Location: /system/helpers/out.php */