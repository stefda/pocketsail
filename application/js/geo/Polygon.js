
function Polygon(points) {

    this.points = points;

    /**
     * @returns {String}
     */
    this.toWKT = function() {
        var str = "POLYGON((";
        for (var i = 0; i < this.points.length; i++) {
            str += this.points[i].x + " " + this.points[i].y;
            str += i < this.points.length - 1 ? "," : "";
        }
        str += "))";
        return str;
    };

    /**
     * @returns {Array[google.maps.LatLng]}
     */
    this.toGooglePath = function() {
        var path = [];
        for (var i = 0; i < this.points.length; i++) {
            path.push(this.points[i].toLatLng().toGoogleLatLng());
        }
        return path;
    };

    /**
     * @returns {google.maps.LatLngBounds}
     */
    this.toGoogleBounds = function() {
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < this.points.length; i++) {
            bounds.extend(this.points[i].toLatLng().toGoogleLatLng());
        }
        return bounds;
    };
}

/**
 * @param {Array[google.maps.LatLng]} path
 */
Polygon.fromGooglePath = function(path) {
    var points = [];
    for (var i = 0; i < path.length; i++) {
        var latLng = path[i];
        points.push(new Point(latLng.lng(), latLng.lat()));
    }
    if (points[0].x !== points[points.length - 1].x
            || points[0].y !== points[points.length - 1].y) {
        points[points.length] = points[0];
    }
    return new Polygon(points);
};

/**
 * @param {String} wkt
 */
Polygon.fromWKT = function(wkt) {

    var points = [];

    // Do initial matching
    var pattern = /POLYGON *\( *\((.*)\).*\)/i;
    var matches = pattern.exec(wkt);

    // Matches array remains empty if nothing is matched
    if (matches === null) {
        return null;
    }

    // Explode by comma matched coordinates trimmed off of spaces
    var sPoints = matches[1].trim().split(",");

    // Iterate over coordinates to instantiate Points of the Polygons
    for (var i = 0; i < sPoints.length; i++) {
        // Parse xy point coordinates
        var pattern = / *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) */;
        var matches = pattern.exec(sPoints[i]);
        // Return null if matching fails
        if (matches.length === 0) {
            return null;
        }
        // Instantiate next Polygon point from matched coordinates
        var x = parseFloat(matches[1]);
        var y = parseFloat(matches[3]);
        points.push(new Point(x, y));
    }

    // Polygon's first and last coordinate must match
    if (!points[0].equals(points[points.length - 1])) {
        return null;
    }

    // Finally, instantiate and return Polygon
    return new Polygon(points);
};