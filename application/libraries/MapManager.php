<?php

class MapManager {

    public function hash($width, $height, $zoom, $latLng, $id, $url, $ids, $types) {

        $bounds = NULL;
        $poi = NULL;
        $labels = [];
        $action = '';
        $card = '';

        if ($id !== 0 || $url !== '') {
            if ($url !== '') {
                $poi = POIModel::loadByUrl($url);
            } else {
                $poi = POIModel::load($id);
            }
            $labels = [LabelModel::loadDynamic($poi->id())];
        }

        if ($latLng === NULL || $zoom === NULL) {
            if ($poi->border() === NULL) {
                $bounds = Bounds::getBounds($width, $height, 14, $poi->latLng());
            } else {
                $bounds = Bounds::fromPolygon($poi->border());
                $zoom = $bounds->getMaxZoom($width, $height);
                $bounds = Bounds::getBounds($width, $height, $zoom, $bounds->getCenter());
            }
        } else {
            $bounds = Bounds::getBounds($width, $height, $zoom, $latLng);
        }

        if (count($types) > 0) {
            $labels = array_merge($labels, LabelModel::loadDynamicByBounds($bounds, $types));
        }

        if ($poi !== NULL) {
            CL_Output::get_instance()->assign('poi', $poi);
            $card = view_get_html('templates/card');
        }

        if ($poi !== NULL || count($ids) > 0 || count($types) > 0) {
            $exceptIds = $poi !== NULL ? array_merge([$poi->id()], $ids) : $ids;
            $labels = array_merge($labels, LabelModel::loadStaticDynamicByBounds($bounds, $zoom, $exceptIds, $types));
            $action = 'relabel';
        } else {
            $labels = LabelModel::loadStaticByBounds($bounds, $zoom);
        }

        return [
            'labels' => $labels,
            'action' => $action,
            'card' => $card,
            'zoom' => $zoom,
            'center' => $bounds->getCenter()
        ];
    }
    
    public function normal($width, $height, $zoom, $latLng, $id, $url, $ids, $types) {

        $bounds = NULL;
        $poi = NULL;
        $labels = [];
        $action = '';
        $card = '';

        if ($id !== 0 || $url !== '') {
            if ($url !== '') {
                $poi = POIModel::loadByUrl($url);
            } else {
                $poi = POIModel::load($id);
            }
            $labels = [LabelModel::loadDynamic($poi->id())];
        }

        if ($latLng === NULL) {
            $bounds = Bounds::getBounds($width, $height, $zoom, $poi->latLng());
        } else {
            $bounds = Bounds::getBounds($width, $height, $zoom, $latLng);
        }

        if (count($ids) > 0) {
            $labels = array_merge($labels, LabelModel::loadDynamicByIds($ids));
        }

        if (count($types) > 0) {
            $labels = array_merge($labels, LabelModel::loadDynamicByBounds($bounds, $types));
        }

        if ($poi !== NULL) {
            CL_Output::get_instance()->assign('poi', $poi);
            $card = view_get_html('templates/card');
        }

        if ($poi !== NULL || count($ids) > 0 || count($types) > 0) {
            $exceptIds = $poi !== NULL ? array_merge([$poi->id()], $ids) : $ids;
            $labels = array_merge($labels, LabelModel::loadStaticDynamicByBounds($bounds, $zoom, $exceptIds, $types));
            $action = 'relabel';
        } else {
            $labels = LabelModel::loadStaticByBounds($bounds, $zoom);
        }

        return [
            'labels' => $labels,
            'action' => $action,
            'card' => $card
        ];
    }

    public function click($width, $height, $zoom, $id, $url) {

        $poi = NULL;
        $bounds = NULL;
        $center = NULL;
        $card = '';

        if ($url !== '') {
            $poi = POIModel::loadByUrl($url);
        } else {
            $poi = POIModel::load($id);
        }

        $out = CL_Output::get_instance();
        $out->assign('poi', $poi);
        $card = view_get_html('templates/card');

        if ($poi->border() !== NULL) {
            $bounds = ViewBounds::fromPolygon($poi->border());
        }

        if ($bounds !== NULL) {
            $zoom = min($zoom, $bounds->getMaxZoom($width, $height));
            $center = $bounds->getCenter();
        } else {
            $center = $poi->latLng();
        }
        
        $bounds = Bounds::getBounds($width, $height, $zoom, $center);
        $labels = LabelModel::loadDynamic($poi->id());

        $labels = [];
        $labels[] = LabelModel::loadDynamic($poi->id());
        $labels = array_merge($labels, LabelModel::loadStaticDynamicByBounds($bounds, $zoom, [$poi->id()], []));

        return [
            'zoom' => $zoom,
            'center' => $center->toWKT(),
            'card' => $card,
            'labels' => $labels,
            'action' => 'relabel',
            'url' => $poi->url(),
            'id' => $poi->id()
        ];
    }

    public function search($width, $height, $zoom, $latLng, $id, $url, $types) {

        $poi = NULL;
        $bounds = NULL;
        $labels = [];
        $card = '';

        if ($id !== 0) {
            $poi = POIModel::load($id);
        } else if ($url !== '') {
            $poi = POIModel::loadByUrl($url);
        }

        // Load card if poi id or URL
        if ($poi !== NULL) {
            $out = CL_Output::get_instance();
            $out->assign('poi', $poi);
            $card = view_get_html('templates/card');
            if ($poi->border() !== NULL) {
                $bounds = Bounds::fromPolygon($poi->border());
                $zoom = $bounds->getMaxZoom($width, $height);
            } else {
                $bounds = Bounds::getBounds($width, $height, $zoom, $poi->latLng());
                $zoom = 14;
            }
            $bounds = Bounds::getBounds($width, $height, $zoom, $bounds->getCenter());
            $labels = [LabelModel::loadDynamic($poi->id())];
        } else {
            $bounds = Bounds::getBounds($width, $height, $zoom, $latLng);
        }

        if (count($types) > 0) {
            while (!LabelModel::oneOfTypesWithinBounds($bounds, $types, $poi === NULL ? 0 : $poi->id()) && --$zoom > 0) {
                $bounds = Bounds::getBounds($width, $height, $zoom, $latLng);
            }
            $labels = array_merge($labels, LabelModel::loadDynamicByBounds($bounds, $types, $poi === NULL ? 0 : $poi->id()));
        }

        $labels = array_merge($labels, LabelModel::loadStaticDynamicByBounds($bounds, $zoom, [$poi === NULL ? 0 : $poi->id()], $types));

        return [
            'zoom' => $zoom,
            'center' => $bounds->getCenter()->toWKT(),
            'card' => $card,
            'labels' => $labels,
            'action' => 'relabel'
        ];
    }

}
