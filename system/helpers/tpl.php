<?php

function tpl_edit($sub) {

    $load = CL_Loader::get_instance();
    $editViewsPath = 'tpl/edit/';
    $subViewPath = $editViewsPath . $sub;

    if (view_exists($subViewPath)) {
        return view_get_html($subViewPath);
    } else {
        $load->model('POITypeModel');
        $type = POITypeModel::catFromSub($sub);
        $catViewPath = $editViewsPath . $type->id;
        if (file_exists($catViewPath)) {
            return view_get_html($catViewPath);
        }
    }

    return view_get_html($editViewsPath . 'default');
}

function attr_tpl_edit($attrName, $attribute) {
    global $attr;
    $attr = $attribute;
    return view_get_html('tpl/edit/attrs/' . $attrName);
}

function attr_tpl_view($attrName, $attribute) {
    global $attr;
    $attr = $attribute;
    return view_get_html('templates/view/attrs/' . $attrName);
}

function a() {
    global $attr;
    $args = func_get_args();
    $temp = &$attr;
    foreach ($args AS $arg) {
        if (is_object($temp) && property_exists($temp, $arg)) {
            $temp = &$temp->{$arg};
        } else {
            return NULL;
        }
    }
    if (is_object($temp) && property_exists($temp, 'val')) {
        return $temp->val;
    } elseif (is_array($temp)) {
        return $temp;
    } else {
        return NULL;
    }
}

function v($attr) {
    $args = func_get_args();
    array_shift($args);
    $temp = &$attr;
    foreach ($args AS $arg) {
        if (is_object($temp) && property_exists($temp, $arg)) {
            $temp = &$temp->{$arg};
        } else {
            return NULL;
        }
    }
    if (is_object($temp) && property_exists($temp, 'val')) {
        return $temp->val;
    } elseif (is_array($temp)) {
        return $temp;
    } else {
        return NULL;
    }
}
