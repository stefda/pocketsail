
/**
 * GeoJSON is a format for encoding a variety of geographic data structures.
 * A GeoJSON object may represent a geometry, a feature, or a collection of
 * features. GeoJSON supports the following geometry types: Point, LineString,
 * Polygon, MultiPoint, MultiLineString, MultiPolygon, and GeometryCollection.
 * Features in GeoJSON contain a geometry object and additional properties,
 * and a feature collection represents a list of features.
 * 
 * @constructor
 * @param {String} type
 * @param {Array} coordinates
 * @property {String} type GeoJSON type
 * @property {Array} coordinates Coordinates of positions
 * @returns {GeoJSON}
 */
function GeoJSON(type, coordinates) {

    this.type = type;
    this.coordinates = coordinates;

    /**
     * Get the type of the object.
     * @returns {String}
     */
    this.getType = function () {
        return this.type;
    };

    /**
     * Get the coordinates.
     * @returns {Array}
     */
    this.getCoordinates = function () {
        return this.coordinates;
    };

    /**
     * Convert to GeoJSON representation.
     * @returns {Object}
     */
    this.toGeoJson = function () {
        return {
            'type': this.type,
            'coordinates': this.coordinates
        };
    };
}