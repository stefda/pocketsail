<?php

define('GEO_R', 6371);
define('GEO_2_Y_LIM', 3.1313013314716462 * 2);
define('GEO_TILE_SIZE', 256);
define('GEO_2_PI', 2 * M_PI);
define('GEO_ZOOMPREC', 3);

abstract class GeoJSON implements CL_Serializable {

    protected $type;
    protected $coordinates;

    protected function __construct($type, $coordinates) {
        $this->type = $type;
        $this->coordinates = $coordinates;
    }

    public static function from_geo_json($geoJson) {
        error('This should never get called.');
    }

    /**
     * Get the type of the object.
     * @returns string
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Get the coordinates.
     * @returns array
     */
    public function get_coordinates() {
        return $this->coordinates;
    }

    /**
     * Convert to GeoJSON representation.
     * @returns stdClass
     */
    public function to_geo_json() {
        return [
            'type' => $this->type,
            'coordinates' => $this->coordinates
        ];
    }

    public function js() {
        return json_encode($this->to_geo_json());
    }

    public static function deserialize($data) {
        $geoJson = deserialize($data, [
            'type' => FILTER_SANITIZE_STRING,
            'coordinates' => [
                'filter' => FILTER_VALIDATE_FLOAT,
                'flags' => FILTER_REQUIRE_ARRAY
            ]
        ]);
        return static::from_geo_json($geoJson);
    }

}
