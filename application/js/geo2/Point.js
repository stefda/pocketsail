
/**
 * A representation of a point in the Cartesian coordinate system.
 * 
 * @example
 * var point = new Point(0, 0) // Point representing the origin in the x-y plane
 * 
 * @constructor
 * @param {Number} x
 * @param {Number} y
 * @property {Number} x X coordinate
 * @property {Number} y Y coordinate
 */
function Point(x, y) {

    this.x = x;
    this.y = y;

    /**
     * Convert the coordinates into a valid GeoJSON Point object.
     * @returns {Object}
     */
    this.toGeoJson = function () {
        return {
            'type': "Point",
            'coordinates': [this.x, this.y]
        };
    };

    /**
     * Get the coordinates as an array of points on the x-y plane.
     * @returns {Array}
     */
    this.getCoordinates = function () {
        return [this.x, this.y];
    };
}

/**
 * Create Point from a GeoJSON Point object.
 * @param {Object} geoJson GeoJSON Point object
 * @returns {Point}
 */
Point.fromGeoJson = function (geoJson) {

    if (typeof geoJson.type === "undefined"
            || geoJson.type !== "Point"
            || typeof geoJson.coordinates === "undefined") {
        throw new Error("fromGeoJson(): First argument must be a valid GeoJSON Point object.");
    }

    return new Point(geoJson.coordinates[0], geoJson.coordinates[1]);
};