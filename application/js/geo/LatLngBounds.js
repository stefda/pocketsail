
/**
 * A representation of a bounding box defined by its southwest and northeast
 * corners. If parameters are omitted the corners are undefined.
 * 
 * @example
 * var sw = new LatLng(0, 0);
 * var ne = new LatLng(10, 10);
 * var bounds = new LatLngBounds(sw, ne);
 * 
 * @constructor
 * @param {LatLng} [sw]
 * @param {LatLng} [ne]
 * @extends GeoJSON
 */
function LatLngBounds(sw, ne) {

    if (typeof sw === "undefined") {
        sw = new LatLng(90, 180);
        ne = new LatLng(-90, -180);
    }

    if (typeof ne === "undefined") {
        ne = new LatLng(sw.lat(), sw.lng());
    }

    GeoJSON.apply(this, ['LatLngBounds', [[sw.lng(), sw.lat()], [ne.lng(), ne.lat()]]]);

    /**
     * Extends the bounds by the given coordinates. If the coordinates are
     * within the existing bounding box the corners remain unchanged.
     * @param {LatLng} latLng Coordinates to extend the bounds
     */
    this.extend = function (latLng) {

        var lat = latLng.lat();
        var lng = latLng.lng();

        var north = this.coordinates[1][1];
        var east = this.coordinates[1][0];
        var south = this.coordinates[0][1];
        var west = this.coordinates[0][0];

        if (north < south) {
            north = south = lat;
        } else {
            if (lat < south) {
                south = lat;
            } else if (lat > north) {
                north = lat;
            }
        }

        if (360 === west - east) {
            west = east = lng;
        } else {
            if (!this._eastWestContainsLng(east, west, lng)) {
                if (this._wrapLng(lng, west) < this._wrapLng(east, lng)) {
                    west = lng;
                } else {
                    east = lng;
                }
            }
        }

        this.coordinates[1][1] = north;
        this.coordinates[1][0] = east;
        this.coordinates[0][1] = south;
        this.coordinates[0][0] = west;
    };

    this._wrapLng = function (a, b) {
        var c = b - a;
        return 0 <= c ? c : b + 180 - (a - 180);
    };

    this._eastWestContainsLng = function (east, west, lng) {
        -180 === lng && (lng = 180);
        return west > east ? (lng >= west || lng <= east) && !(360 === west - east) : lng >= west && lng <= east;
    };

    this.getNorthEast = function () {
        return new LatLng(this.coordinates[1][1], this.coordinates[1][0]);
    };

    this.getSouthWest = function () {
        return new LatLng(this.coordinates[0][1], this.coordinates[0][0]);
    };

    /**
     * Get north edge latitude.
     * @returns {Number}
     */
    this.getNorth = function () {
        return this.coordinates[1][1];
    };

    /**
     * Get east edge latitude.
     * @returns {Number}
     */
    this.getEast = function () {
        return this.coordinates[1][0];
    };

    /**
     * Get south edge latitude.
     * @returns {Number}
     */
    this.getSouth = function () {
        return this.coordinates[0][1];
    };

    /**
     * Get west edge longitude.
     * @returns {Number}
     */
    this.getWest = function () {
        return this.coordinates[0][0];
    };

    /**
     * Compute the center of the bounds.
     * @returns {LatLng}
     */
    this.getCenter = function () {
        var bottomLeft = Proj.latlng2merc(this.getSouthWest());
        var topRight = Proj.latlng2merc(this.getNorthEast());
        var x = bottomLeft.x() > topRight.x() ? (bottomLeft.x() + topRight.x() + Math.PI * 2) / 2 : (bottomLeft.x() + topRight.x()) / 2;
        var y = (bottomLeft.y() + topRight.y()) / 2;
        return Proj.merc2latLng(new Point(x, y));
    };

    /**
     * Computes the maximum integer zoom that will accomodate the bounds within
     * the given mercator map dimensions.
     * 
     * @example
     * var bounds = new LatLngBounds(new LatLng(0, 0), new LatLng(1, 1));
     * var zoom = bounds.getMaxZoom(800, 600, 10, 20);
     * 
     * @param {Number} width Map width in pixels
     * @param {Number} height Map height in pixels
     * @param {Number} [top] Top padding (0 padding used if ommited)
     * @param {Number} [right] Right padding (top padding used if ommited)
     * @param {Number} [bottom] Bottom padding (top padding used if ommited)
     * @param {Number} [left] Left padding (right padding used if ommited)
     * @returns {Number}
     */
    this.getMaxZoom = function (width, height, top, right, bottom, left) {

        // Compute padding
        if (!left) {
            if (!bottom) {
                if (!right) {
                    left = bottom = right = top = !top ? 0 : top;
                } else {
                    bottom = top;
                    left = right;
                }
            } else {
                left = right;
            }
        }

        var sw = this.getSouthWest();
        var ne = this.getNorthEast();

        // Subtract padding
        width -= right + left;
        height -= top + bottom;

        // Project onto the mercator plane
        var A = Proj.latlng2merc(sw);
        var B = Proj.latlng2merc(ne);

        // Compute the zoom for both directions
        var z_x = Math.log((Proj.DOUBLEPI * width) / (Proj.TILESIZE * Math.abs(A.x() - B.x()))) / Math.log(2);
        var z_y = Math.log((Proj.DOUBLEYLIM * height) / (Proj.TILESIZE * Math.abs(A.y() - B.y()))) / Math.log(2);

        var zf = Math.pow(10, Proj.ZOOMPREC);
        z_x = Math.round(z_x * zf) / zf;
        z_y = Math.round(z_y * zf) / zf;

        return Math.max(0, Math.floor(Math.min(z_x, z_y)));
    };

    /**
     * Complete the bounds into a polygon.
     * @returns {Polygon}
     */
    this.toPolygon = function () {
        return new Polygon([[
                [this.coordinates[0][0], this.coordinates[0][1]],
                [this.coordinates[0][0], this.coordinates[1][1]],
                [this.coordinates[1][0], this.coordinates[1][1]],
                [this.coordinates[1][0], this.coordinates[0][1]],
                [this.coordinates[0][0], this.coordinates[0][1]]
            ]]);
    };

    /**
     * Convert bounds into google map bounds.
     * @returns {google.maps.LatLngBounds}
     */
    this.toGoogleLatLngBounds = function () {
        return new google.maps.LatLngBounds(
                this.getSouthWest().toGoogleLatLng(),
                this.getNorthEast().toGoogleLatLng());
    };

    this.toString = function () {
        return 'LatLngBounds((' +
                this.coordinates[0][1] + ',' + this.coordinates[0][0] + '),(' +
                this.coordinates[1][1] + ',' + this.coordinates[1][0] +
                '))';
    };
}

LatLngBounds.prototype = GeoJSON.prototype;
LatLngBounds.prototype.constructor = LatLngBounds;

/**
 * Create bounds from a GeoJSON "LatLngBounds" object.
 * @param {Object} geoJson GeoJSON object
 * @returns {LatLngBounds}
 */
LatLngBounds.fromGeoJson = function (geoJson) {
    var coordinates = geoJson.coordinates;
    var sw = new LatLng(coordinates[0][1], coordinates[0][0]);
    var ne = new LatLng(coordinates[1][1], coordinates[1][0]);
    return new LatLngBounds(sw, ne);
};

/**
 * Create bounds that contain given polygon.
 * 
 * @param {Polygon} polygon Polygon object
 * @return LatLngBounds
 */
LatLngBounds.fromPolygon = function (polygon) {

    var bounds = new LatLngBounds();

    if (polygon.size() === 0) {
        return bounds;
    }

    // Use first ring only
    var ring = polygon.getRing(0);

    // Extend bounds by all positions in ring, one by one
    for (var i = 0; i < ring.length; i++) {
        var position = ring[i];
        bounds.extend(new LatLng(position[1], position[0]));
    }

    return bounds;
};

/**
 * Create bounds from google map bounds.
 * 
 * @param {google.maps.LatLngBounds} bounds
 * @returns {LatLngBounds}
 */
LatLngBounds.fromGoogleLatLngBounds = function (bounds) {
    var sw = bounds.getSouthWest();
    var ne = bounds.getNorthEast();
    return new LatLngBounds(LatLng.fromGoogleLatLng(sw), LatLng.fromGoogleLatLng(ne));
};