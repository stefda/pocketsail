
var LatLng = function(lat, lng) {

    this.lat = lat;
    this.lng = lng;

    /**
     * @param {LatLng} latLng
     * @returns {Boolean}
     */
    this.equals = function(latLng) {
        return this.lat === latLng.lat && this.lng === latLng.lng;
    };

    /**
     * @returns {google.maps.LatLng}
     */
    this.toGoogleLatLng = function() {
        return new google.maps.LatLng(this.lat, this.lng);
    };

    /**
     * @returns {Point}
     */
    this.toPoint = function() {
        return new Point(this.lng, this.lat);
    };

    /**
     * @returns {String}
     */
    this.toWKT = function() {
        return this.toPoint().toWKT();
    };
};

/**
 * @param {google.maps.LatLng} gLatLng
 * @returns {LatLng}
 */
LatLng.fromGoogleLatLng = function(gLatLng) {
    return new LatLng(gLatLng.lat(), gLatLng.lng());
};

/**
 * @param {String} wkt
 * @returns {LatLng}
 */
LatLng.fromWKT = function(wkt) {
    var point = Point.fromWKT(wkt);
    if (point === null) {
        return null;
    }
    return new LatLng(point.y, point.x);
};