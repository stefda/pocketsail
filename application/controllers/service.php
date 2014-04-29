<?php

class Service extends CL_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('ps');
        $this->load->library('Point');
        $this->load->library('Polygon');
        $this->load->model('POI');
    }

    /**
     * @AjaxCallable=TRUE
     * @AjaxAsync=TRUE
     * @AjaxMethod=GET
     */
    function place_info($poiID, $type) {
        
        $poi = POIModel::load($poiID);
        $info = info_remove_empty($poi->get_info());
        return t('marina', $info, $type);
    }

}