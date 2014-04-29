<?php

class Google {

    private static $API_PLACES = 'https://maps.googleapis.com/maps/api/place/nearbysearch/';

    /**
     * Updates given $types array with closest corresponding places that are
     * found using Google Places API.
     * 
     * @param Point $p
     * @param array $types
     * @param int $pages
     */
    public static function place_range(Point $p, &$types, $pages = 5) {

        function ff($val) {
            return $val === FALSE;
        }

        $location = $p->lat() . ',' . $p->lng();
        $key = CL_Config::get_instance()->get_item('main', 'google_key');
        $nextPageToken = '';

        for ($i = 0; $i < $pages; $i++) {
            $typesStr = implode('|', array_keys(array_filter($types, 'ff')));
            $query = http_build_query([
                'location' => $location,
                'rankby' => 'distance',
                'types' => $typesStr,
                'sensor' => 'false',
                'key' => $key,
                'next_page_token' => $nextPageToken
            ]);
            $o = json_decode(@file_get_contents(self::$API_PLACES . 'json?' . $query));
            if ($o === NULL || $o->status == 'ZERO_RESULTS' || $o->status == 'REQUEST_DENIED' || $o->status == 'INVALID_REQUEST') {
                break;
            }
            foreach ($o->results AS $r) {
                $common = array_intersect($r->types, array_keys($types));
                if (count($common) == 0) {
                    continue;
                }
                $type = reset($common);
                if (!$types[$type]) {
                    $pPlace = new Point($r->geometry->location->lat, $r->geometry->location->lng);
                    $types[$type] = new stdClass();
                    $types[$type]->distance = Geo::haversine($pPlace, $p);
                    $types[$type]->name = $r->name;
                    $types[$type]->location = $r->geometry->location;
                }
            }
            if (property_exists($o, 'next_page_token')) {
                $nextPageToken = $o->next_page_token;
            } else {
                break;
            }
        }
    }

}