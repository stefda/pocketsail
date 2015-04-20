
function Point(x, y) {

    this.x = x;
    this.y = y;

    /**
     * @param {Point} point
     * @returns {Boolean}
     */
    this.equals = function(point) {
        return this.x === point.x && this.y === point.y;
    };

    /**
     * @returns {LatLng}
     */
    this.toLatLng = function() {
        return new LatLng(this.y, this.x);
    };

    /**
     * @returns {String}
     */
    this.toWKT = function() {
        return "POINT(" + this.x + " " + this.y + ")";
    };
}

Point.fromWKT = function(wkt) {

    var pattern = /POINT *\( *(-?\d+(\.\d+)?) +(-?\d+(\.\d+)?) *\)/i;
    var matches = pattern.exec(wkt);

    if (matches === null) {
        return null;
    }

    // Extract point coordinates from matches
    var x = parseFloat(matches[1]);
    var y = parseFloat(matches[3]);

    return new Point(x, y);
};