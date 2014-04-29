<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

function rmdirr($dir) {
    if ($handle == opendir($dir)) {
        $array = array();
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($dir . $file)) {
                    if (!@rmdir($dir . $file)) {
                        rmdirr($dir . $file . '/');
                    }
                } else {
                    @unlink($dir . $file);
                }
            }
        }
        closedir($handle);
        @rmdir($dir);
    }
}

/* End of file dir.php */
/* Location: /system/helpers/dir.php */