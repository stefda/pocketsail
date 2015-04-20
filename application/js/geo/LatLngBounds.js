
/**
 * A representation of a bounding box on earth defined by its southwest and
 * northeast corners. If parameters are omitted the corners remain undefined.
 * 
 * @example
 * var sw = new LatLng(0, 0);
 * var ne = new LatLng(10, 10);
 * var bounds = new LatLngBounds(sw, ne);
 * 
 * @constructor
 * @param {LatLng} [sw]
 * @param {LatLng} [ne]
 * @property {LatLng} sw The southwest corner
 * @property {LatLng} ne The northeast corner
 */
function LatLngBounds(sw, ne) {

    if (typeof sw === "undefined") {
        sw = ne = null;
    }

    if (typeof ne === "undefined") {
        ne = new LatLng(sw.lat, sw.lng);
    }

    this.sw = sw;
    this.ne = ne;

    /**
     * Extends the bounds by the given coordinates. If the coordinates are
     * within the existing bounding box the corners remain unchanged.
     * @param {LatLng} latLng Coordinates to extend the bounds
     */
    this.extend = function (latLng) {

        if (this.sw === null) {
            this.sw = new LatLng(latLng.lat, latLng.lng);
            this.ne = new LatLng(latLng.lat, latLng.lng);
            return;
        }

        var sw = this.sw;
        var ne = this.ne;

        this.sw.lat = Math.min(latLng.lat, this.sw.lat);
        this.sw.lng = Math.min(latLng.lng, this.sw.lng);
        this.ne.lat = Math.max(latLng.lat, this.ne.lat);
        this.ne.lng = Math.max(latLng.lng, this.ne.lng);
    };

    /**
     * Get north edge latitude.
     * @returns {Number}
     */
    this.getNorth = function () {
        return this.ne !== null ? this.ne.lat : undefined;
    };

    /**
     * Get east edge latitude.
     * @returns {Number}
     */
    this.getEast = function () {
        return this.ne !== null ? this.ne.lng : undefined;
    };

    /**
     * Get south edge latitude.
     * @returns {Number}
     */
    this.getSouth = function () {
        return this.sw !== null ? this.sw.lat : undefined;
    };

    /**
     * Get west edge longitude.
     * @returns {Number}
     */
    this.getWest = function () {
        return this.sw !== null ? this.sw.lng : undefined;
    };

    /**
     * Compute the center of the bounds.
     * @returns {LatLng}
     */
    this.getCenter = function () {
        return new LatLng((this.sw.lat + this.ne.lat) / 2, (this.sw.lng + this.ne.lng) / 2);
    };

    /**
     * Computes the maximum integer zoom that will accomodate the bounds within
     * the given mercator map dimensions. Optionally, padding values can be
     * provided.
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

        // Subtract padding
        width -= right + left;
        height -= top + bottom;

        // Project onto the mercator plane
        var A = Proj.latlng2merc(this.sw);
        var B = Proj.latlng2merc(this.ne);

        // Compute the zoom for both directions
        var z_x = Math.log((Proj.DOUBLEPI * width) / (Proj.TILESIZE * Math.abs(A.x - B.x))) / Math.log(2);
        var z_y = Math.log((Proj.DOUBLEYLIM * height) / (Proj.TILESIZE * Math.abs(A.y - B.y))) / Math.log(2);

        var zf = Math.pow(10, Proj.ZOOMPREC);
        z_x = Math.round(z_x * zf) / zf;
        z_y = Math.round(z_y * zf) / zf;

        return Math.floor(Math.min(z_x, z_y));
    };

    /**
     * Complete the bounds into a valid polygon.
     * @returns {Polygon}
     */
    this.toPolygon = function () {
        return new Polygon([
            this.sw.getCoordinates(),
            [this.ne.getCoordinates()[0], this.sw.getCoordinates()[1]],
            this.ne.getCoordinates(),
            [this.sw.getCoordinates()[0], this.ne.getCoordinates()[1]],
            this.sw.getCoordinates()
        ]);
    };
}