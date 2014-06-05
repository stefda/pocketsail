
/**
 * @param {Object} o
 * @returns {Map}
 */
function Map(o) {

    var canvas = o.canvas;
    var zoom = o.zoom;
    var center = o.center;

    this.types = o.types !== undefined ? o.types : [];
    this.poiId = o.poiId !== undefined ? o.poiId : [];
    this.flags = o.flags !== undefined ? o.flags : [];
    this.ignoreZoomChange = false;
    this.init = true;
    this.markers = {};
    this.googleMap = null;

    // Assign closure
    var this_ = this;

    /**
     * @returns {Array}
     */
    this.getTypes = function() {
        return this.types;
    };

    /**
     * @returns {Number}
     */
    this.getPoiId = function() {
        return this.poiId;
    };
    
    /**
     * @returns {Array}
     */
    this.getFlags = function() {
        return this.flags;
    };

    /**
     * @returns {LatLng}
     */
    this.getCenter = function() {
        return LatLng.fromGoogleLatLng(this.googleMap.getCenter());
    };

    /**
     * @returns {Number}
     */
    this.getZoom = function() {
        return this.googleMap.getZoom();
    };

    /**
     * @returns {ViewBounds}
     */
    this.getViewBounds = function() {
        return ViewBounds.fromMap(this.googleMap);
    };

    this.setTypes = function(types) {
        this.types = types;
    };

    this.setPoiId = function(poiId) {
        this.poiId = poiId;
    };

    this.setFlags = function(flags) {
        this.flags = flags;
    };

    this.addMarker = function(marker) {
        this.markers.push(marker);
    };

    this.clearMarkers = function() {
        for (var i = 0; i < this.markers.length; i++) {
            this.markers[i].setMap(null);
        }
        this.markers = [];
    };

    this.loadData = function() {
        APIBroker.loadData({
            post: {
                vBounds: ViewBounds.fromMap(this.googleMap).toWKT(),
                zoom: this.getZoom(),
                types: this.getTypes(),
                poiId: this.getPoiId(),
                flags: this.getFlags()
            },
            success: function(res) {
                this_.handleResult(res);
            }
        });
    };

    /**
     * @param {Object} res
     */
    this.handleResult = function(res) {

        if (res.center !== undefined && res.zoom !== undefined) {
            var center = LatLng.fromWKT(res.center);
            var zoom = res.zoom;
            this.ignoreIdle = true;
            this.panTo(center, zoom);
        }

        var labels = res.labels;
        var flags = res.flags;

        for (var i = 0; i < labels.length; i++) {
            labels[i] = Label.deserialize(labels[i], this.getZoom());
        }

        if (flags.indexOf('doLabelling') !== -1) {
            Labeller.doLabelling(labels);
        }

        this.clearMarkers();

        for (var i = 0; i < labels.length; i++) {
            var marker = new Marker({
                map: this_,
                label: labels[i]
            });
            this.addMarker(marker);
        }
    };

    /**
     * @param {Number} zoom
     */
    this.setZoom = function(zoom) {
        this.ignoreZoomChange = true;
        this.googleMap.setZoom(zoom);
    };

    /**
     * @param {LatLng} center
     */
    this.setCenter = function(center) {
        this.googleMap.setCenter(center.toGoogleLatLng());
    };

    /**
     * @param {LatLng} center
     * @param {Number} zoom
     */
    this.panTo = function(center, zoom) {
        this.setCenter(center);
        this.setZoom(zoom);
    };

    this.initGoogleMap = function() {

        // Initialise custom map style
        var styledMap = new google.maps.StyledMapType(mapStyle, {name: "PocketSail"});

        // Create new map
        this.googleMap = new google.maps.Map(document.getElementById(canvas), {
            zoom: zoom,
            center: center.toGoogleLatLng(),
            panControl: false,
            streetViewControl: false,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM,
                style: google.maps.ZoomControlStyle.SMALL
            }
        });

        // Set map to custom style
        this.googleMap.mapTypes.set('map_style', styledMap);
        this.googleMap.setMapTypeId('map_style');

        // Load data on the first idle
        google.maps.event.addListener(this.googleMap, 'idle', function() {
            // Skip all idles except the first one
            if (this_.init) {
                this_.init = false;
                this_.loadData();
            }
        });

        google.maps.event.addListener(this.googleMap, 'maptypeid_changed', function() {
            if (this.getMapTypeId() === 'hybrid') {
                $('#mapStyle').attr('href', '/application/layout/map-satellite.css');
            } else {
                $('#mapStyle').attr('href', '/application/layout/map.css');
            }
        });

        // Load data on dragend
        google.maps.event.addListener(this.googleMap, 'dragend', function() {
            this_.loadData();
        });

        // Load data on zoom_change
        google.maps.event.addListener(this.googleMap, 'zoom_changed', function() {
            if (this_.ignoreZoomChange) {
                this_.ignoreZoomChange = false;
                return;
            }
            this_.loadData();
        });
    };

    // Initialise google map
    this.initGoogleMap();
}