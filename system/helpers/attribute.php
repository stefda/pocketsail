<?php

function attribute_add_template($ftTypeID) {

    $o = CL_Output::get_instance();
    $l = CL_Loader::get_instance();
    $ftTypeModel = FeatureTypeModel::get_instance();
    $o->assign('ftTypeID', $ftTypeID);
    $o->assign('ftTypeName', $ftTypeModel->ID_to_name($ftTypeID));

    $class = $ftTypeModel->ID_to_class($ftTypeID);
    $folder = 'attribute_add/';
    $path = APPPATH . 'views/' . $folder;

    if (file_exists($path . $class . '.php')) {
        return $l->view($folder . $class, FALSE);
    }
    return $l->view($folder . 'default', FALSE);
}

function attribute_view_template(AttributeModel $attr) {

    $o = CL_Output::get_instance();
    $l = CL_Loader::get_instance();
    $o->assign('attr', $attr);

    $folder = 'attribute_view/';
    $path = APPPATH . 'views/' . $folder;

    if (file_exists($path . $attr->get_type_ID() . '.php')) {
        return $l->view($folder . $attr->get_type_ID(), FALSE);
    }
    return $l->view($folder . 'default', FALSE);
}

function attribute_edit_template(AttributeModel $attr) {

    $o = CL_Output::get_instance();
    $l = CL_Loader::get_instance();
    $o->assign('attr', $attr);

    $folder = 'attribut_edit/';
    $path = APPPATH . 'views/' . $folder;

    if (file_exists($path . $attr->get_type_ID() . '.php')) {
        return $l->view($folder . $attr->get_type_ID(), FALSE);
    }
    return $l->view($folder . 'default', FALSE);
}