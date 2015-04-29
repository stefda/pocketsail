
/**
 * An interface for computing spatial projectsion.
 * 
 * @example
 * var latLng = new LatLng(51.524940, -0.138332);
 * var point = Proj.latLng2merc(latLng); // Location of the Warren Street tube station projected on the Mercator plane
 * 
 * @constructor
 */
function Proj() {
}

/**
 * Computes given location in web Mercator (WGS 84). The maximum and minimum
 * latitude values are 85 and -85 degrees, respectively. Values outside of these
 * limits are clipped correspondingly.
 * @param {LatLng} latLng
 * @returns {Point}
 */
Proj.latlng2merc = function (latLng) {

    var lat = latLng.lat;
    var lng = latLng.lng;

    lat = lat > 85 ? 85 : (lat < -85 ? -85 : lat);
    lng = lng > 180 ? 180 : (lng < -180 ? -180 : lng);

    var lam = lng * Proj.DTR;
    var phi = -lat * Proj.DTR;
    var x = lam;
    var y = -1 * Math.log((Math.tan(0.5 * (Proj.HALFPI - phi))));

    return new Point(x, y);
};

/**
 * Computes pixel position on a scaled mercator plane for the given location.
 * Zoom is used to compute the size of the plane in pixels, i.e. width =
 * height = {@link Proj.TILESIZE} * 2^zoom.
 * @param {LatLng} latLng Location
 * @param {Number} zoom Zoom level
 * @returns {Point}
 */
Proj.latlng2pixel = function (latLng, zoom) {

    var point = this.latlng2merc(latLng);
    var scale = Proj.TILESIZE * Math.pow(2, zoom);

    var x = Math.round((point.x + Proj.XLIM) / Proj.DOUBLEPI * scale);
    var y = Math.round((point.y + Proj.YLIM) / Proj.DOUBLEYLIM * scale);

    return new Point(x, y);
};

/**
 * 
 * @param {Point} point 
 * @returns {LatLng}
 */
Proj.merc2latLng = function (point) {

    var lng = point.x / Proj.DTR;
    var lat = -(Math.atan(Math.pow(Math.E, point.y)) / 0.5 - Proj.HALFPI) / Proj.DTR;

    return new LatLng(lat, lng);
};

Proj.pixel2latLng = function (point, zoom) {

    var scale = Proj.TILESIZE * Math.pow(2, zoom);

    var x = point.x() / scale * Proj.DOUBLEPI - Proj.XLIM;
    var y = point.y() / scale * Proj.DOUBLEYLIM - Proj.YLIM;
    
    return this.merc2latLng(new Point(x, y));
};

/**
 * Size of the tile that is used to compute the width and height of the
 * mercator plane in the {@link Proj.latlng2merc} method. Defaults to 256.
 * @static
 */
Proj.TILESIZE = 256;

/**
 * Decimal precision used to round the resulting zoom in the {@link
 * LatLngBounds#getMaxZoom} method. Defaults to three decimal places.
 * @static
 */
Proj.ZOOMPREC = 3;

Proj.HALFPI = Math.PI / 2;
Proj.DTR = Math.PI / 180;
Proj.YLIM = 3.1313013314716462;
Proj.DOUBLEYLIM = Proj.YLIM * 2;
Proj.XLIM = Math.PI;
Proj.DOUBLEPI = Math.PI * 2;