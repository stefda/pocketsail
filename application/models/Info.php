<?php

abstract class InfoNode {

    public abstract function get_view_html();

    public abstract function get_edit_html();

    public abstract function set_places($places);
}

abstract class InfoLeaf {

    public abstract function get_view_html();

    public abstract function get_edit_html();
}

class MarinaInfo extends InfoNode {

    private $description;
    private $anchoring;
    private $goingOut;

    public function __construct($info) {
        $this->description = @$info->description;
        $this->anchoring = new AnchoringInfo(@$info->anchoring);
        $this->goingOut = new GoingOutInfo(@$info->goingOut);
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $html = '';
        $html .= $this->description;
        $html .= $this->anchoring->get_view_html();
        $html .= $this->goingOut->get_view_html();
        return $html;
    }

    public function set_places($places) {
        $this->anchoring->set_places($places);
        $this->goingOut->set_places($places);
    }

}

/**
 * Anchoring
 */
class AnchoringInfo extends InfoNode {

    private $description;
    private $anchorages;

    public function __construct($info) {
        $this->description = @$info->description;
        $this->anchorages = [];
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $html = '';
        $html .= '<div>';
        $html .= '<h2 class="anchoring">Anchoring</h2>';
        $html .= $this->description;
        $html .= '<div class="anchorages">';
        foreach ($this->anchorages AS $anchorage) {
            $html .= $anchorage->get_view_html();
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public function set_places($places) {
        $anchorages = @$places->anchorage;
        foreach ($anchorages AS $anchorage) {
            $this->anchorages[] = new AnchorageInfo($anchorage);
        }
    }

}

class AnchorageInfo extends InfoLeaf {

    private $place;

    public function __construct(Place $anchorage) {
        $this->place = $anchorage;
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $info = $this->place->get_info();
        $html = '';
        $html .= '<div class="anchorage">';
        $html .= @$info->description;
        $html .= '</div>';
        return $html;
    }

}

/**
 * Going Out
 */
class GoingOutInfo extends InfoNode {

    private $description;
    private $restaurants;
    private $bars;
    private $cafes;
    private $clubs;

    public function __construct($info) {
        $this->description = @$info->description;
        $this->restaurants = [];
        $this->bars = [];
        $this->cafes = [];
        $this->clubs = [];
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $html = '';
        $html .= '<div>';
        $html .= '<h2 class="going_out">Going Out</h2>';
        $html .= $this->description;
        $html .= '<div class="restaurants">';
        foreach ($this->restaurants AS $restaurant) {
            $html .= $restaurant->get_view_html();
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public function set_places($places) {
        $restaurants = @$places->restaurant;
        foreach ($restaurants AS $restaurant) {
            $this->restaurants[] = new RestaurantInfo($restaurant);
        }
    }

}

class RestaurantInfo extends InfoLeaf {

    private $place;

    public function __construct(Place $restaurant) {
        $this->place = $restaurant;
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $info = $this->place->get_info();
        $html = '';
        $html .= '<div class="restaurant">';
        $html .= @$info->description;
        $html .= '</div>';
        return $html;
    }

}

class BarInfo extends InfoLeaf {

    private $place;

    public function __construct(Place $bar) {
        $this->place = $bar;
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $html = '';
        return $html;
    }

    public function set_places($places) {
        return NULL;
    }

}

class CafeInfo extends InfoNode {

    private $description;

    public function __construct($info) {
        $this->description = @$info->description;
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $html = '';
        return $html;
    }

    public function set_places($places) {
        return NULL;
    }

}

class ClubInfo extends InfoNode {

    private $description;

    public function __construct($info) {
        $this->description = @$info->description;
    }

    public function get_edit_html() {
        $html = '';
        return $html;
    }

    public function get_view_html() {
        $html = '';
        return $html;
    }

    public function set_places($places) {
        return NULL;
    }

}