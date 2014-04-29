
function Bounds(n, e, s, w) {

    this.n = n;
    this.e = e;
    this.s = s;
    this.w = w;

    this.toGoogleBounds = function() {
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(this.n, this.e));
        bounds.extend(new google.maps.LatLng(this.s, this.w));
        return bounds;
    };

    this.serialize = function() {
        return {
            n: this.n,
            e: this.e,
            s: this.s,
            w: this.w
        };
    };
}

Bounds.fromGoogleBounds = function(gBounds) {
    var ne = gBounds.getNorthEast();
    var sw = gBounds.getSouthWest();
    return new Bounds(ne.lat(), ne.lng(), sw.lat(), sw.lng());
};