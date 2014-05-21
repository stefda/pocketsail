
function Geo() {
    // Intentionally left empty
}

/**
 * Wraps latitudes greater than 90° or smaller than -90° around the globe.
 * 
 * @param {double} lat
 * @returns {double}
 */
Geo.wrapLat = function(lat) {
    var phi = lat * Math.PI / 180;
    return Math.atan(Math.sin(phi) / Math.abs(Math.cos(phi))) * 180 / Math.PI;
};

/**
 * Wraps longitudes greater than 180° or smaller than -180° around the globe.
 * 
 * @param {double} lng
 * @returns {double}
 */
Geo.wrapLng = function(lng) {
    var lambda = lng * Math.PI / 180;
    return Math.atan2(Math.sin(lambda), Math.cos(lambda)) * 180 / Math.PI;
};

Geo.trueCenter = function(center) {
    var lat = Geo.wrapLat(center.lat);
    var lng = Geo.wrapLng(center.lng % 360);
    return new LatLng(lat, lng);
};