
/**
 * Coordinates of a LineString are an array of points.
 * 
 * @example
 * var lineString = new LineString([[0, 0], [1, 1]]);
 * 
 * @constructor
 * @param {Array} points
 * @extends GeoJSON
 */
function LineString(points) {

    GeoJSON.apply(this, ['LineString', points]);
}

LineString.prototype = GeoJSON.prototype;
LineString.prototype.constructor = LineString;

/**
 * Create LineString from a GeoJSON "LineString" object.
 * @param {Object} geoJson GeoJSON object
 * @returns {LineString}
 */
Point.fromGeoJson = function (geoJson) {
    return new LineString(geoJson.coordinates[0], geoJson.coordinates[1]);
};