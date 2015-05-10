
/**
 * A representation of a polygon in the Cartesian plane, coordinates must be an
 * array of linear rings. For Polygons with multiple rings, the first must be
 * the exterior ring and any others must be interior rings or holes.
 * 
 * @example
 * var polygon = new Polygon([[[0, 0], [0, 1], [1, 1], [1, 0], [0, 0]]]);
 * 
 * @constructor
 * @param {Array} coordinates
 * @extends GeoJSON
 */
function Polygon(coordinates) {

    GeoJSON.apply(this, ['Polygon', coordinates]);

    /**
     * Get the coordinates array fo the i-th ring.
     * @param {Number} i Ring position
     * @returns {Array}
     */
    this.getRing = function (i) {
        return this.coordinates[i];
    };

    /**
     * Number of linear rings in the polygon.
     * @returns {Number}
     */
    this.size = function () {
        return this.coordinates.length;
    };

    /**
     * Get the n-th point of the i-th ring.
     * @param {Number} i Position of the ring in the polygon
     * @param {Number} n Position of the point in the i-th ring
     * @returns {Point}
     */
    this.getPoint = function (i, n) {

        if (i > this.size() - 1) {
            throw "PolygonException: Accessing undefined liear ring with index '" + i + "'";
        }

        if (n > 1) {
            throw "PolygonException: Accessing undefined position with index '" + n + "'";
        }

        var position = this.coordinates[i][n];
        return new Point(position[0], position[1]);
    };

    this.toGoogleArray = function () {

        if (this.size() === 0) {
            return [];
        }

        var coordinates = [];
        var ring = this.getRing(0);

        for (var i = 0; i < ring.length - 1; i++) {
            coordinates[i] = new google.maps.LatLng(ring[i][1], ring[i][0]);
        }

        return coordinates;
    };

    this.toString = function () {
        return '[' + this.getRing(0).join() + ']';
    };
}

Polygon.prototype = GeoJSON.prototype;
Polygon.prototype.constructor = Polygon;

/**
 * Create Polygon from a GeoJSON "Polygon" object.
 * @param {Object} geoJson GeoJSON object
 * @returns {Polygon}
 */
Polygon.fromGeoJson = function (geoJson) {
    if (geoJson && geoJson.type === 'Polygon' && geoJson.coordinates) {
        return new Polygon(geoJson.coordinates);
    }
    return null;
};

Polygon.fromGooglePolygon = function (polygon) {

    var latLngs = polygon.getPath().getArray();
    var coordinates = [[]];

    var i = 0;
    for (; i < latLngs.length; i++) {
        coordinates[0][i] = [latLngs[i].lng(), latLngs[i].lat()];
    }
    coordinates[0][i] = [latLngs[0].lng(), latLngs[0].lat()];

    return new Polygon(coordinates);
};