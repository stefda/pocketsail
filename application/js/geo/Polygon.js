
/**
 * A representation of a polygon in the Cartesian plane. Formally, the first
 * point of the polygon must be equal to the first point. This is not enforced,
 * however.
 * 
 * @example
 * var polygon = new Polygon([0, 0], [0, 1], [1, 1], [1, 0], [0, 0]);
 * 
 * @constructor
 * @param {Array} points
 * @property {Array} points An array of points that constitue the polygon
 */
function Polygon(points) {

    this.points = points;

    /**
     * Convert polygon points into a valid GeoJSON Polygon object.
     * @returns {Object}
     */
    this.toGeoJson = function () {
        return {
            'type': "Polygon",
            'coordinates': this.points
        };
    };

    /**
     * Get the points as an array of point coordinates on the x-y plane.
     * @returns {Array}
     */
    this.getCoordinates = function () {
        return this.points;
    };

    /**
     * Get the i-th point as an array of coordinates on the x-y plane.
     * @param {Number} i Position of the point coordinates
     * @returns {Array}
     */
    this.getCoordinatesAt = function (i) {
        return this.points[i];
    };

    /**
     * Get the i-th point.
     * @param {Number} i Position of the point
     * @returns {Point}
     */
    this.getPointAt = function (i) {
        return new Point(this.points[i]);
    };

}

/**
 * Create Polygon from a GeoJSON Polygon object.
 * @param {Object} geoJson GeoJSON Polygon object
 * @returns {Polygon}
 */
Polygon.fromGeoJson = function (geoJson) {

    if (typeof geoJson.type === "undefined"
            || geoJson.type !== "Polygon"
            || typeof geoJson.coordinates === "undefined") {
        throw new Error("fromGeoJson(): First argument must be a valid GeoJSON Polygon object.");
    }

    return new Polygon(geoJson.coordinates);
}