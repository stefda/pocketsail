
function Projector() {
    // Left blank
}

Projector.HALFPI = Math.PI / 2;
Projector.DTR = Math.PI / 180;
Projector.YLIM = 3.1313013314716462;
Projector.DOUBLEYLIM = 3.1313013314716462 * 2;
Projector.XLIM = Math.PI;
Projector.DOUBLEPI = Math.PI * 2;
Projector.TILESIZE = 256;

Projector.mercator = function(latLng, zoom) {
    var lat = latLng.lat;
    var lng = latLng.lng;
    var scale = Projector.TILESIZE * Math.pow(2, zoom);
    lat = lat > 85 ? 85 : (lat < -85 ? -85 : lat);
    lng = lng > 180 ? 180 : (lng < -180 ? -180 : lng);
    var lam = lng * Projector.DTR;
    var phi = -lat * Projector.DTR;
    var x = lam;
    var y = -1 * Math.log((Math.tan(0.5 * (Projector.HALFPI - phi))));
    var left = Math.round((x + Projector.XLIM) / Projector.DOUBLEPI * scale);
    var top = Math.round((y + Projector.YLIM) / Projector.DOUBLEYLIM * scale);
    return new Position(left, top);
};