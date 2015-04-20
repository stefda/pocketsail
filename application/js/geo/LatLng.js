
/**
 * A representation of a point on eart in the starndard coordinate system.
 * 
 * @example
 * var latLng = new LatLng(51.524940, -0.138332); // Location of the Warren Street tube station
 * 
 * @constructor
 * @param {Number} lat Latitude
 * @param {Number} lng Longitude
 * @property {Number} lat Latitude
 * @property {Number} lng Longitude
 */
function LatLng(lat, lng) {

    this.lat = lat;
    this.lng = lng;

    /**
     * Convert the coordinates into a valid GeoJSON Point object.
     * @returns {Object}
     */
    this.toGeoJson = function () {
        return {
            'type': "Point",
            'coordinates': [this.lng, this.lat]
        };
    };

    /**
     * Get the coordinates as an array of points on the x-y plane.
     * @returns {Array}
     */
    this.getCoordinates = function () {
        return [this.lng, this.lat];
    };

    /**
     * Get the coordinates are a point in the x-y plane.
     * @returns {Point}
     */
    this.getPoint = function () {
        return new Point([this.lng, this.lat]);
    };
}

/**
 * Create coordinates from a GeoJSON Point object.
 * @param {Object} geoJson GeoJSON Point object
 * @returns {LatLng}
 */
LatLng.fromGeoJson = function (geoJson) {

    if (typeof geoJson.type === "undefined"
            || geoJson.type !== "Point"
            || typeof geoJson.coordinates === "undefined") {
        throw new Error("fromGeoJson(): First argument must be a valid GeoJSON Point object.");
    }

    return new LatLng(geoJson.coordinates[1], geoJson.coordinates[0]);
};