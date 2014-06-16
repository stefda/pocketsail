
/**
 * @param {Object} o
 * @returns {Map}
 */
function Map(o) {

    var canvas = o.canvas;
    var zoom = o.zoom;
    var center = o.center;
    var cursor = o.cursor === undefined ? 'auto' : o.cursor;
    var border = o.border;

    this.cache = o.cache !== undefined ? o.cache : false;
    this.types = o.types !== undefined ? o.types : [];
    this.poiId = o.poiId !== undefined ? o.poiId : 0;
    this.poiIds = o.poiIds !== undefined ? o.poiIds : [];
    this.flags = o.flags !== undefined ? o.flags : [];
    this.ignoreZoomChange = false;
    this.init = true;
    this.markers = {};
    this.newMarkers = {};
    this.googleMap = null;

    this.markerClickFx = function(marker, pos) {
        $('#editMenu').menu({
            top: pos.y,
            left: pos.x,
            select: function(e, ui) {
                if (ui.item.value === 'edit') {
                    window.location = '/poi/edit?poiId=' + marker.id
                }
            }
        });
    };

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
    this.getPoiIds = function() {
        return this.poiIds;
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

    this.setPoiIds = function(poiIds) {
        this.poiIds = poiIds;
    };

    this.setFlags = function(flags) {
        this.flags = flags;
    };

    this.addMarker = function(marker) {
        this.markers.push(marker);
    };

    this.addNewMarker = function(marker) {
        this.newMarkers.push(marker);
    };

    this.clearMarkers = function() {
        for (var i = 0; i < this.markers.length; i++) {
            this.markers[i].setMap(null);
        }
        this.markers = [];
    };

    this.clearNewMarkers = function() {
        for (var i = 0; i < this.newMarkers.length; i++) {
            this.newMarkers[i].setMap(null);
        }
        this.newMarkers = [];
    };

    this.loadData = function(flags) {

        // Normalise flags
        if (flags === undefined) {
            flags = [];
        }

        APIBroker.loadData({
            post: {
                vBounds: ViewBounds.fromMap(this.googleMap).toWKT(),
                zoom: this.getZoom(),
                types: this.getTypes(),
                poiId: this.getPoiId(),
                poiIds: this.getPoiIds(),
                flags: this.getFlags().concat(flags)
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
        this.clearNewMarkers();

        for (var i = 0; i < labels.length; i++) {
            var marker = new Marker({
                map: this_,
                label: labels[i]
            });
            this.addMarker(marker);
        }

        // Display new markers
        if (res.new !== undefined) {
            for (var i = 0; i < res.new.length; i++) {
                var poi = res.new[i];
                var position = LatLng.fromWKT(poi.latLng).toGoogleLatLng();
                console.log(poi);
                marker = new google.maps.Marker({
                    map: this.googleMap,
                    position: position
                });
                this.addNewMarker(marker);
            }
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

    this.addListener = function(type, fx) {
        google.maps.event.addListener(this.googleMap, type, function(e) {
            fx.call(this, e);
        });
    };

    this.initGoogleMap = function() {

        // Initialise custom map style
        var styledMap = new google.maps.StyledMapType(mapStyle, {name: "PocketSail"});

        // If no center or zoom given, try cookies or set fixed
        if (center === undefined || zoom === undefined) {

            var userLat = get_cookie('psMapLat');
            var userLng = get_cookie('psMapLng');
            var userZoom = get_cookie('psMapZoom');

            if (userLat && userLng && userZoom) {
                userLat = parseFloat(userLat);
                userLng = parseFloat(userLng);
                userZoom = parseInt(userZoom);
                center = new LatLng(userLat, userLng);
                zoom = userZoom;
            } else {
                center = new LatLng(44, 16);
                zoom = 12;
            }
        }

        var PS_MAPTYPE_ID = 'PocketSail';

        // Create new map
        this.googleMap = new google.maps.Map(document.getElementById(canvas), {
            zoom: zoom,
            center: center.toGoogleLatLng(),
            panControl: false,
            streetViewControl: false,
            scaleControl: true,
            draggableCursor: cursor,
            mapTypeControlOptions: {
                mapTypeIds: [PS_MAPTYPE_ID, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID]
            },
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM,
                style: google.maps.ZoomControlStyle.SMALL
            }
        });

        // Set map to custom style
        this.googleMap.mapTypes.set(PS_MAPTYPE_ID, styledMap);
        this.googleMap.setMapTypeId(PS_MAPTYPE_ID);

        // Fit map to given border, if
        if (border) {
            var bounds = border.toGoogleBounds();
            this.googleMap.fitBounds(bounds);
        }

        // Load data on the first idle
        google.maps.event.addListener(this.googleMap, 'idle', function() {
            // Skip all idles except the first one
            if (this_.init) {
                this_.init = false;
                this_.loadData();
            }

            if (this_.cache) {
                var center = this.getCenter();
                set_cookie('psMapLat', center.lat());
                set_cookie('psMapLng', center.lng());
                set_cookie('psMapZoom', this.getZoom());
            }
        });

//        google.maps.event.addListener(this.googleMap, 'maptypeid_changed', function() {
//            if (this.getMapTypeId() === 'hybrid') {
//                $('#mapStyle').attr('href', '/application/layout/map-satellite.css');
//                this_.googleMap.setOptions({
//                    styles: [{
//                            featureType: "all",
//                            elementType: "labels",
//                            stylers: [{visibility: "off"}]
//                        },
//                        {
//                            featureType: "road",
//                            stylers: [{visibility: "off"}]}
//                    ]
//                });
//            } else {
//                $('#mapStyle').attr('href', '/application/layout/map.css');
//            }
//        });

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