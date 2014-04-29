
function Polygon(points) {

    this.points = points;

    this.serialize = function() {
        var points = [];
        for (var i = 0; i < this.points.length; i++) {
            points.push(this.points[i].serialize());
        }
        return points;
    };

    this.toGooglePath = function() {
        var path = [];
        for (var i = 0; i < this.points.length; i++) {
            path.push(this.points[i].toLatLng().toGoogleLatLng());
        }
        return path;
    };

    this.toGoogleBounds = function() {
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < this.points.length; i++) {
            bounds.extend(this.points[i].toLatLng().toGoogleLatLng());
        }
        return bounds;
    };
}

Polygon.fromGooglePath = function(path) {
    var points = [];
    for (var i = 0; i < path.getLength(); i++) {
        var latLng = path.getAt(i);
        points.push(new Point(latLng.lng(), latLng.lat()));
    }
    if (points[0].x !== points[points.length - 1].x
            || points[0].y !== points[points.length - 1].y) {
        points[points.length] = points[0];
    }
    return new Polygon(points);
};

Polygon.deserialize = function(sPolygon) {
    if (sPolygon === null) {
        return null;
    }
    var points = [];
    for (var i = 0; i < sPolygon.length; i++) {
        points.push(Point.deserialize(sPolygon[i]));
    }
    return new Polygon(points);
};