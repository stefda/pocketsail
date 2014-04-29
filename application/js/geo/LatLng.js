
var LatLng = function(lat, lng) {

    this.lat = lat;
    this.lng = lng;

    this.toGoogleLatLng = function() {
        return new google.maps.LatLng(this.lat, this.lng);
    };

    this.serialize = function() {
        return {
            lat: this.lat,
            lng: this.lng
        };
    };
    
    this.toString = function() {
        return '[' + this.lat + ',' + this.lng + ']';
    };
};

LatLng.fromGoogleLatLng = function(gLatLng) {
    return new LatLng(gLatLng.lat(), gLatLng.lng());
};

LatLng.deserialize = function(sLatLng) {
    return new LatLng(sLatLng.lat, sLatLng.lng);
};