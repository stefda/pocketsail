
/**
 * @param {String} canvas
 * @param {LatLng} center
 * @param {Number} zoom
 * @returns {Map}
 */
function Map(canvas, center, zoom) {

    this.types = ['marina', 'gasstation'];
    this.poiInfo = null;
    this.ignoreIdle = false;

    // Assign closure
    var this_ = this;

    this.map = new google.maps.Map(document.getElementById(canvas), {
        zoom: zoom,
        center: center.toGoogleLatLng()
    });

    google.maps.event.addListener(this.map, 'idle', function() {

        if (this_.ignoreIdle) {
            this_.ignoreIdle = false;
            return;
        }

        var vBounds = ViewBounds.fromMap(this_.map);
        var zoom = this_.map.getZoom();
        var types = this_.types;
        var poiId = this_.poiInfo === null ? null : this.poiInfo.id;

        MapBroker.loadData({
            post: {
                vBounds: vBounds.toWKT(),
                zoom: zoom,
                types: types,
                poiId: poiId
            },
            success: function(res) {
                this_.handleResult(res);
            }
        });
    });

    /**
     * @returns {Number}
     */
    this.getZoom = function() {
        return this.map.getZoom();
    };

    /**
     * @returns {ViewBounds}
     */
    this.getViewBounds = function() {
        return ViewBounds.fromMap(this.map);
    };

    this.setTypes = function(types) {
        this.types = types;
    };

    this.setPoiInfo = function(poiInfo) {
        this.poiInfo = poiInfo;
    };

    this.handleResult = function(res) {

        if (res.center !== undefined && res.zoom !== undefined) {
            var center = LatLng.fromWKT(res.center);
            var zoom = res.zoom;
            this.ignoreIdle = true;
            this.panTo(center, zoom);
        }

        var labels = res.labels;
        
        console.log(labels);
    };

    /**
     * @param {LatLng} center
     * @param {Number} zoom
     */
    this.panTo = function(center, zoom) {
        this.map.setCenter(center.toGoogleLatLng());
        this.map.setZoom(zoom);
        console.log(zoom);
    };
}