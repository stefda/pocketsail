
/**
 * A representation of a position on earth in the starndard coordinate system.
 * 
 * @example
 * var latLng = new LatLng(51.524940, -0.138332); // Position of the Warren Street tube station
 * 
 * @constructor
 * @param {Number} lat Latitude
 * @param {Number} lng Longitude
 * @extends GeoJSON
 */
function LatLng(lat, lng) {

    lat = lat > 90 ? 90 : (lat < -90 ? -90 : lat);
    lng = lng <= 180 ? lng >= -180 ? lng : 180 + (lng - 180) % 360 : -180 + (lng + 180) % 360;

    GeoJSON.apply(this, ['Point', [lng, lat]]);

    /**
     * Get latitude.
     * @returns {Number}
     */
    this.lat = function () {
        return this.coordinates[1];
    };

    /**
     * Get longitude.
     * @returns {Number}
     */
    this.lng = function () {
        return this.coordinates[0];
    };

    /**
     * Convert the coordinates into a google.maps.LatLng object.
     * @returns {google.maps.LatLng}
     */
    this.toGoogleLatLng = function () {
        return new google.maps.LatLng(this.lat(), this.lng());
    };

    /**
     * Get the coordinates as a Point object.
     * @returns {Point}
     */
    this.toPoint = function () {
        return new Point(this.coordinates[0], this.coordinates[1]);
    };

    this.toString = function () {
        return 'LatLng(' + this.coordinates[1] + ',' + this.coordinates[0] + ')';
    };
}

LatLng.prototype = GeoJSON.prototype;
LatLng.prototype.constructor = LatLng;

LatLng.containsLat = function (north, south, lat) {
    return !(lat > north || lat < south);
};

/**
 * Create coordinates from a GeoJSON "Point" object.
 * @param {Object} geoJson GeoJSON object
 * @returns {LatLng}
 */
LatLng.fromGeoJson = function (geoJson) {
    return new LatLng(geoJson.coordinates[1], geoJson.coordinates[0]);
};

/**
 * Create coordinates from a google maps LatLng object.
 * @param {google.maps.LatLng} latLng LatLng object
 * @returns {LatLng}
 */
LatLng.fromGoogleLatLng = function (latLng) {
    return new LatLng(latLng.lat(), latLng.lng());
};