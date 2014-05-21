
function ViewBounds(ne, sw) {

    this.n = ne.lat;
    this.e = ne.lng;
    this.s = sw.lat;
    this.w = sw.lng;

    /**
     * @returns {google.maps.LatLngBounds}
     */
    this.toGoogleBounds = function() {
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(this.n, this.e));
        bounds.extend(new google.maps.LatLng(this.s, this.w));
        return bounds;
    };

    /**
     * @returns {String}
     */
    this.toWKT = function() {
        return "LINESTRING(" + this.e + " " + this.n
                + "," + this.w + " " + this.s + ")";
    };
}

/**
 * @param {google.maps.LatLngBounds} gBounds
 * @returns {ViewBounds}
 */
ViewBounds.fromGoogleBounds = function(gBounds) {
    var ne = LatLng.fromGoogleLatLng(gBounds.getNorthEast());
    var sw = LatLng.fromGoogleLatLng(gBounds.getSouthWest());
    return new Bounds(ne, sw);
};

/**
 * @param {String} wkt
 * @returns {ViewBounds}
 */
ViewBounds.fromWKT = function(wkt) {

    // Start with using the wkt string to create a LineString
    var ls = LineString.fromWKT(wkt);

    // Return NULL if the LineString is NULL or doesn't containt exacly two
    // points
    if (ls === null || ls.size() !== 2) {
        return null;
    }

    // Assign the two points to bounds' corners
    var ne = ls.getPointAt(0).toLatLng();
    var sw = ls.getPointAt(1).toLatLng();

    // Return NULL if the point are wrongly coordinated
    if (ne.lat < sw.lat || ne.lng < sw.lng) {
        return null;
    }

    // Finally, return correctly initialised Bounds
    return new ViewBounds(ne, sw);
};