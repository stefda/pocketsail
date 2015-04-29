
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

    this.id = 0;
    this.url = '';
    this.ids = [];
    this.types = [];

//    this.cache = o.cache !== undefined ? o.cache : false;
    this.ignoreZoomChange = false;
    this.ignoreHash = false;
    this.init = true;
    this.labels = [];
    this.markers = {};
    this.map = null;
    this.ready = null;

    if (o.markerContextMenu !== undefined && o.markerContextMenu) {
        this.markerContextmenu = function (marker, pos) {

            $('#editMenu').mapmenu({
                top: pos.y,
                left: pos.x,
                select: function (e, ui) {
                    if (ui.item.value === 'edit') {
                        window.location = '/poi/edit?poiId=' + marker.id;
                    }
                }
            });
        };
    }

    if (o.markerClick !== undefined && o.markerClick) {
        this.markerClick = function (marker, pos) {

            this.id = marker.id;
            this.url = marker.url;
            this.ids = [];
            this.types = [];

            API2Broker.loadData({
                post: {
                    width: this.getWidth(),
                    height: this.getHeight(),
                    zoom: this.getZoom(),
                    center: this.getCenter().toWKT(),
                    id: marker.id,
                    url: marker.url,
                    action: 'click'
                },
                success: function (res) {
                    this_.ignoreHash = true;
                    window.location.hash = res.url !== '' ? res.url : res.id;
                    this_.handleResult(res);
                }
            });
        };
    }

    var this_ = this;

    this.redrawMarkers = function () {

        this.clearMarkers();

        for (var i = 0; i < this.labels.length; i++) {
            var marker = new Marker({
                map: this_,
                label: this.labels[i]
            });
            this.addMarker(marker);
        }
    };

    this.resetLabels = function (labels) {
        this.labels = [];
        for (var i = 0; i < labels.length; i++) {
            this.labels.push(Label.deserialize(labels[i], this.getZoom()));
        }
    };

    this.relabel = function () {
        Labeller.doLabelling(this.labels);
    };

    this.processHash = function (hash, callback) {

        if (this.ignoreHash) {
            this.ignoreHash = false;
            return;
        }

        this.id = hash.id;
        this.url = hash.url;
        this.types = hash.types;

        API2Broker.loadData({
            post: {
                action: 'hash',
                width: this.getWidth(),
                height: this.getHeight(),
                zoom: hash.zoom === null ? 14 : hash.zoom,
                center: (hash.lat === null && hash.lng === null) ? null : (new LatLng(hash.lat, hash.lng).toWKT()),
                id: hash.id,
                url: hash.url,
                ids: [],
                types: hash.types
            },
            success: function (res) {
                callback(res);
            }
        });
    };

    this.loadData = function (action, callback) {

        if (action === undefined) {
            action = 'normal';
        }

        API2Broker.loadData({
            post: {
                action: action,
                width: this.getWidth(),
                height: this.getHeight(),
                zoom: this.getZoom(),
                center: this.getCenter().toWKT(),
                id: this.id,
                url: this.url,
                ids: this.ids,
                types: this.types
            },
            success: function (res) {
                //this_.handleResult(res);
                callback(res);
            }
        });
    };

    this.updateMap = function (res) {

        var action = res.action !== undefined ? res.action : '';
        var zoom = res.zoom === undefined ? this.getZoom() : res.zoom;

        if (res.center !== undefined) {
            var center = LatLng.fromWKT(res.center);
            this.panTo(center, zoom);
        }

        if (res.labels !== undefined) {
            this.resetLabels(res.labels);
        }

        if (action === 'relabel') {
            this.relabel();
        }

        if (res.card !== undefined && res.card !== '') {
            this.showCard(res.card);
        }
    };

    this.handleResult = function (res) {

        var action = res.action !== undefined ? res.action : '';
        var zoom = res.zoom === undefined ? this.getZoom() : res.zoom;

        if (res.center !== undefined) {
            var center = LatLng.fromWKT(res.center);
            this.panTo(center, zoom);
        }

        if (res.labels !== undefined) {
            this.resetLabels(res.labels);
        }

        if (action === 'relabel') {
            this.relabel();
        }

        if (res.card !== undefined && res.card !== '') {
            this.showCard(res.card);
        }

        this.redrawMarkers();
    };

    this.getWidth = function () {
        return $('#' + canvas).innerWidth();
    };

    this.getHeight = function () {
        return $('#' + canvas).innerHeight();
    };

    this.getTypes = function () {
        return this.types;
    };

    this.getPoiId = function () {
        return this.poiId;
    };

    this.getPoiIds = function () {
        return this.poiIds;
    };

    this.getFlags = function () {
        return this.flags;
    };

    this.getCenter = function () {
        return LatLng.fromGoogleLatLng(this.map.getCenter());
    };

    this.getZoom = function () {
        return this.map.getZoom();
    };

    this.getViewBounds = function () {
        return ViewBounds.fromMap(this.map);
    };

    this.setTypes = function (types) {
        this.types = types;
    };

    this.setPoiId = function (id) {
        this.id = id;
    };

    this.setPoiUrl = function (url) {
        this.url = url;
    };

    this.setPoiIds = function (ids) {
        this.ids = ids;
    };

    this.setFlags = function (flags) {
        this.flags = flags;
    };

    this.addMarker = function (marker) {
        this.markers.push(marker);
    };

    this.addNewMarker = function (marker) {
        this.newMarkers.push(marker);
    };

    this.clearMarkers = function () {
        for (var i = 0; i < this.markers.length; i++) {
            this.markers[i].setMap(null);
        }
        this.markers = [];
    };

    this.showCard = function (html) {
        $('#card').html(html);
        $('#card').show();
    };

    this.hideCard = function () {
        $('#card').hide();
    };

    this.setZoom = function (zoom) {
        this.ignoreZoomChange = true;
        this.map.setZoom(zoom);
    };

    this.setCenter = function (center) {
        this.map.panTo(center.toGoogleLatLng());
    };

    this.panTo = function (center, zoom) {
        this.setZoom(zoom);
        this.setCenter(center);
    };

    this.addListener = function (type, fx) {
        google.maps.event.addListener(this.map, type, function (e) {
            fx.call(this, e);
        });
    };

    this.initGoogleMap = function () {

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
        this.map = new google.maps.Map(document.getElementById(canvas), {
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
        this.map.mapTypes.set(PS_MAPTYPE_ID, styledMap);
        this.map.setMapTypeId(PS_MAPTYPE_ID);

        // Fit map to given border, if
        if (border) {
            var bounds = border.toGoogleBounds();
            this.map.fitBounds(bounds);
        }

        // Load data on the first idle
        google.maps.event.addListener(this.map, 'idle', function () {

            if (this_.init) {
                this_.init = false;
                this_.ready !== null ? this_.ready(this_) : false;
//                this_.loadData('normal', function (res) {
//                    this_.handleResult(res);
//                });
            }

            if (this_.cache) {
                var center = this.getCenter();
                set_cookie('psMapLat', center.lat());
                set_cookie('psMapLng', center.lng());
                set_cookie('psMapZoom', this.getZoom());
            }
        });

        google.maps.event.addListener(this.map, 'dragend', function () {
            this_.loadData('normal', function (res) {
                this_.handleResult(res);
            });
        });

        google.maps.event.addListener(this.map, 'zoom_changed', function () {
            if (this_.ignoreZoomChange) {
                this_.ignoreZoomChange = false;
                return;
            }
            this_.loadData('normal', function (res) {
                this_.handleResult(res);
            });
        });
    };
}