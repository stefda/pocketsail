
function Point(x, y) {

    this.x = x;
    this.y = y;

    this.serialize = function() {
        return {
            x: this.x,
            y: this.y
        };
    };

    this.toLatLng = function() {
        return new LatLng(this.y, this.x);
    };
}

Point.deserialize = function(sPoint) {
    return new Point(sPoint.x, sPoint.y);
};