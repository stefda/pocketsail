
function ViewBounds(sw, ne) {

    this.s = sw.lat;
    this.w = sw.lng;
    this.n = ne.lat;
    this.e = ne.lng;

    /**
     * @returns {Bounds}
     */
    this.toBounds = function() {
        return new Bounds(new LatLng(this.s, this.w), new LatLng(this.n, this.e));
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

    /**
     * @returns {String}
     */
    this.toWKT = function() {
        return "BOUNDS(" + this.w + " " + this.s
                + "," + this.e + " " + this.n + ")";
    };
}

/**
 * @param {google.maps.Map} map
 * @returns {ViewBounds}
 */
ViewBounds.fromMap = function(map) {

    var bounds = Bounds.fromGoogleBounds(map.getBounds());
    var zoom = map.getZoom();
    var center = LatLng.fromGoogleLatLng(map.getCenter());
    var trueCenter = Geo.trueCenter(center);
    var viewWidth = map.getDiv().clientWidth;
    
    return bounds.toViewBounds(zoom, trueCenter, viewWidth);
};

/**
 * @param {String} wkt
 * @returns {ViewBounds}
 */
ViewBounds.fromWKT = function(wkt) {

    // Do matching
    var pattern = /BOUNDS *\( *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) *, *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) *\)/i;
    var matches = pattern.exec(wkt);

    // Matches array remains empty if nothing is matched
    if (matches === 0) {
        return null;
    }

    // Extract point coordinates from matches
    var w = parseFloat(matches[1]);
    var s = parseFloat(matches[3]);
    var e = parseFloat(matches[5]);
    var n = parseFloat(matches[7]);

    // Initialise bounds' corners
    var sw = new LatLng(s, w);
    var ne = new LatLng(n, e);

    return new ViewBounds(sw, ne);
};

ViewBounds.fromPolygon = function(polygon) {

    points = polygon.points;

    // Initialise extremes with first point's coordinates
    var n = points[0].y;
    var e = points[0].x;
    var s = points[0].y;
    var w = points[0].x;

    // Extend the bounds correspondingly
    for (var i = 1; i < points.length; i++) {
        n = Math.max(n, points[i].y);
        e = Math.max(e, points[i].x);
        s = Math.min(s, points[i].y);
        w = Math.min(w, points[i].x);
    }

    // Instantiate ViewBounds accordingly, then return
    return new ViewBounds(new LatLng(s, w), new LatLng(n, e));
};