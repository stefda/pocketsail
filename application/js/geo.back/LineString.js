
function LineString(points) {

    this.points = points;

    /**
     * @returns {Number}
     */
    this.size = function() {
        return points.length;
    };

    /**
     * @param {Number} i
     * @returns {LatLng}
     */
    this.getPointAt = function(i) {
        if (i < 0 || i > this.points.length - 1) {
            return null;
        }
        return this.points[i];
    };

    /**
     * @returns {String}
     */
    this.toWKT = function() {
        var str = "LINESTRING(";
        for (var i = 0; i < this.points.length; i++) {
            str += this.points[i].x + " " + this.points[i].y;
            str += i !== this.points.length - 1 ? "," : "";
        }
        str += ")";
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
 * @param {String} wkt
 * @returns {LineString}
 */
LineString.fromWKT = function(wkt) {

    points = [];

    // Do initial matching
    var pattern = /LINESTRING *\((.*)\)/i;
    var matches = pattern.exec(wkt);

    // Matches array remains empty if nothing is matched
    if (matches === null) {
        return null;
    }

    // Explode by comma matched coordinates trimmed off of spaces
    var sPoints = matches[1].trim().split(",");

    // If points are fewer than 2 the LineString is actually a point...
    if (sPoints.length < 2) {
        return null;
    }

    // Iterate over coordinates to instantiate Points of the LineString
    for (var i = 0; i < sPoints.length; i++) {
        // Parse xy point coordinates
        var pattern = / *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) */;
        var matches = pattern.exec(sPoints[i]);
        // Return null if matching fails
        if (matches.length === 0) {
            return null;
        }
        // Instantiate next LineString point from matched coordinates
        var x = parseFloat(matches[1]);
        var y = parseFloat(matches[3]);
        points.push(new Point(x, y));
    }

    // Uff, hard work that was!
    return new LineString(points);
};