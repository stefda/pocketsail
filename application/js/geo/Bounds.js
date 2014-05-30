
function Bounds(sw, ne) {

    var s = sw.lat;
    var w = sw.lng;
    var n = ne.lat;
    var e = ne.lng;

    var s = Geo.wrapLat(s);
    var n = Geo.wrapLat(n);

    var lngDiff = e - w;
    if (lngDiff >= 360) {
        e = 180;
        w = -180;
    } else {
        e = Geo.wrapLng(e);
        w = Geo.wrapLng(w);
    }

    this.s = s;
    this.w = w;
    this.n = n;
    this.e = e;

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
        return new ViewBounds(new LatLng(this.s, w), new LatLng(this.n, e));
    };

    /**
     * @returns {google.maps.LatLngBounds}
     */
    this.toGoogleBounds = function() {
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(this.s, this.w));
        bounds.extend(new google.maps.LatLng(this.n, this.e));
        return bounds;
    };
}

/**
 * @param {google.maps.LatLngBounds} gBounds
 * @returns {Bounds}
 */
Bounds.fromGoogleBounds = function(gBounds) {
    var sw = LatLng.fromGoogleLatLng(gBounds.getSouthWest());
    var ne = LatLng.fromGoogleLatLng(gBounds.getNorthEast());
    return new Bounds(sw, ne);
};