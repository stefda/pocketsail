
/**
 * A representation of a point in the Cartesian coordinate system.
 * 
 * @example
 * var point = new Point(0, 0);
 * 
 * @constructor
 * @param {Number} x
 * @param {Number} y
 * @extends GeoJSON
 */
function Point(x, y) {

    GeoJSON.apply(this, ['Point', [x, y]]);

    /**
     * @returns {Number}
     */
    this.x = function () {
        return this.coordinates[0];
    };

    /**
     * @returns {Number}
     */
    this.y = function () {
        return this.coordinates[1];
    };
    
    this.toLatLng = function() {
        return new LatLng(this.y(), this.x());
    };
}

Point.prototype = GeoJSON.prototype;
Point.prototype.constructor = Point;

/**
 * Create Point from a GeoJSON "Point" object.
 * @param {Object} geoJson GeoJSON object
 * @returns {Point}
 */
Point.fromGeoJson = function (geoJson) {
    return new Point(geoJson.coordinates[0], geoJson.coordinates[1]);
};