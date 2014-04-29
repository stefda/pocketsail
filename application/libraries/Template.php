<?php

class Template {

    public static function short($info, $type) {

        $loader = CL_Loader::get_instance();
        $output = CL_Output::get_instance();

        $html = '';
        $output->assign('info', $info);

        if (file_exists(APPPATH . '/views/templates/short/' . $type . '.php')) {
            $html = $loader->view('/templates/short/' . $type, FALSE);
        } else {
            $html = $loader->view('/templates/short/default', FALSE);
        }
        
        return $html;
    }

}
