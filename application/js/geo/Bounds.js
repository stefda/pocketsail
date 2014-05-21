
function Bounds(ne, sw) {

    this.n = ne.lat;
    this.e = ne.lng;
    this.s = sw.lat;
    this.w = sw.lng;

    /**
     * @param {Number} zoom
     * @param {LatLng} trueCenter
     * @param {Number} viewWidth
     * @returns {ViewBounds}
     */
    this.toViewBounds = function(zoom, trueCenter, viewWidth) {
        var worldWidth = 256 * Math.pow(2, zoom);
        var e = trueCenter.lng + viewWidth / worldWidth * 180;
        var w = trueCenter.lng - viewWidth / worldWidth * 180;
        return new Bounds(new LatLng(this.n, e), new LatLng(this.s, w));
    };

    /**
     * @returns {google.maps.LatLngBounds}
     */
    this.toGoogleBounds = function() {
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(this.n, this.e));
        bounds.extend(new google.maps.LatLng(this.s, this.w));
        return bounds;
    };

    this.toWKT = function() {
        return "BOUNDS(" + this.n + " " + this.e
                + "," + this.s + " " + this.w + ")";
    };
}

/**
 * @param {google.maps.LatLngBounds} gBounds
 * @returns {Bounds}
 */
Bounds.fromGoogleBounds = function(gBounds) {
    var ne = LatLng.fromGoogleLatLng(gBounds.getNorthEast());
    var sw = LatLng.fromGoogleLatLng(gBounds.getSouthWest());
    return new Bounds(ne, sw);
};