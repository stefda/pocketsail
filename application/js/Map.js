
function Map(canvas, args) {

    args = args === undefined ? {} : args;

    this.canvas = canvas;
    this.mode = args.mode ? args.mode : 'default';
    this.center = args.center;
    this.zoom = args.zoom;
    this.cursor = args.cursor === undefined ? 'auto' : args.cursor;
    this.cache = args.cache === undefined ? true : args.cache;
    this.borderEdit = args.borderEdit === undefined ? false : args.borderEdit;
    this.disableDefaultUI = args.disableDefaultUI === undefined ? false : args.disableDefaultUI;

    this.params = {
        poiId: undefined,
        poiUrl: undefined,
        poiIds: undefined,
        types: undefined,
        bounds: undefined,
        zoom: undefined
    };

    this.labels = {
        static: [],
        dynamic: []
    };

    this.cardHtml = '';
    this.markers = [];
    this.draggableMarker = null;
    this.onCallbacks = {};

    // Google Map
    this._map = null;
    this.init = false;
    this.loadDataOnIdle = false;
    this.loadDataOnZoomChange = true;
    this.markerClicked = false;
    this.ignoreMarkerClick = false;
    this.zoomChanged = false;
    this.centerChanged = false;
    this.cardChanged = false;

    this.border = {
        polyline: null,
        polygon: null
    };

    // Closure
    var _this = this;

    this.setParam = function (name, value) {
        this.params[name] = value;
    };

    this.clearParam = function (name) {
        this.params[name] = undefined;
    };

    this.setParams = function (params) {
        for (var name in params) {
            this.params[name] = params[name];
        }
    };

    this.clearAllParams = function () {
        for (var name in this.params) {
            this.params[name] = undefined;
        }
    };

    this.setCenter = function (center) {
        this.center = center;
        if (this.init) {
            this._map.setCenter(center.toGoogleLatLng());
        }
    };

    this.paramsAreClear = function () {
        for (var name in this.params) {
            if (typeof this.params[name] !== undefined) {
                return false;
            }
        }
        return true;
    };

    this.resize = function () {
        google.maps.event.trigger(this._map, 'resize');
    };

    this.setCardHtml = function (cardHtml) {
        this.cardHtml = cardHtml;
    };

    this.setZoom = function (zoom) {
        if (this.init) {
            this.loadDataOnZoomChange = false;
            this._map.setZoom(zoom);
        }
    };

    this.panTo = function (center) {
        if (this.init) {
            this._map.panTo(center.toGoogleLatLng());
        }
    };

    this.getCenter = function () {
        if (!this.init) {
            return undefined;
        }
        return LatLng.fromGoogleLatLng(this._map.getCenter());
    };

    this.getBounds = function () {
        if (!this.init) {
            return undefined;
        }
        return LatLngBounds.fromGoogleLatLngBounds(this._map.getBounds());
    };

    this.getZoom = function () {
        if (!this.init) {
            return undefined;
        }
        return this._map.getZoom();
    };

    this.reload = function () {
        this.loadData(function (res) {
            this.handleData(res);
            this.redraw();
        });
    };

    this.loadData = function () {

        var action = typeof arguments[0] === 'string' ? arguments[0] : 'default';
        var callback = typeof arguments[0] === 'function' ? arguments[0] : arguments[1];

        MapBroker.load_data({
            post: {
                action: action,
                mode: this.mode,
                poiId: this.params.poiId,
                poiUrl: this.params.poiUrl,
                poiIds: this.params.poiIds,
                types: this.params.types,
                bounds: this.params.bounds !== undefined ? this.params.bounds.toGeoJson() : this.init ? this.getBounds().toGeoJson() : undefined,
                zoom: this.params.zoom !== undefined ? this.params.zoom : this.getZoom(),
                width: this.canvas.innerWidth(),
                height: this.canvas.innerHeight()
            },
            success: function (res) {
                callback && callback.call(_this, res);
                _this.clearParam('bounds');
                _this.clearParam('zoom');
            }
        });
    };

    this.setLabels = function (labels) {
        if (labels) {
            this.labels.static = labels.static ? labels.static : [];
            this.labels.dynamic = labels.dynamic ? labels.dynamic : [];
        }
    };

    this.redraw = function () {

        if (this.zoomChanged) {
            if (this.centerChanged) {
                this.panTo(this.center);
                this.centerChanged = false;
            }
            this.setZoom(this.zoom);
            this.zoomChanged = false;
        }

        if (this.cardChanged) {
            this.trigger('card_changed', this.cardHtml);
            this.cardChanged = false;
        }

        this.redrawMarkers();
    };

    this.redrawMarkers = function () {

        var dynamic = [];
        var static = [];
        var labelIds = [];
        var zoom = this.getZoom();

        for (var i = 0; i < this.labels.dynamic.length; i++) {
            dynamic.push(Label.deserialize(this.labels.dynamic[i], zoom));
            labelIds.push(this.labels.dynamic[i].id);
        }

        for (var i = 0; i < this.labels.static.length; i++) {
            static.push(Label.deserialize(this.labels.static[i], zoom));
            labelIds.push(this.labels.static[i].id);
        }

        if (dynamic.length !== 0) {
            Labeller.doLabelling(dynamic);
        }

        var labels = dynamic.concat(static);

        // Clear markers
        for (var i = 0; i < this.markers.length; i++) {
            this.markers[i].setMap(null);
        }

        for (var i = 0; i < labels.length; i++) {
            var marker = new Marker({
                map: _this,
                label: labels[i]
            });
            this.markers.push(marker);
        }
    };

    this.clearCardHtml = function () {
        this.cardHtml = '';
    };

    this.handleData = function (data) {

        if (data.zoom) {
            if (data.center) {
                this.center = LatLng.fromGeoJson(data.center);
                this.centerChanged = true;
            }
            this.zoom = data.zoom;
            this.zoomChanged = true;
        }

        if (data.card) {
            this.cardHtml = data.card;
            this.cardChanged = true;
        }

        this.setLabels(data.labels);
    };

    this.initCanvas = function (callback) {

        // If no center or zoom given, try cookies or set fixed
        if (this.center === undefined || this.zoom === undefined) {

            var cacheLat = get_cookie('psMapLat');
            var cacheLng = get_cookie('psMapLng');
            var cacheZoom = get_cookie('psMapZoom');

            if (this.cache && cacheLat && cacheLng && cacheZoom) {
                cacheLat = parseFloat(cacheLat);
                cacheLng = parseFloat(cacheLng);
                cacheZoom = parseInt(cacheZoom);
                this.center = new LatLng(cacheLat, cacheLng);
                this.zoom = cacheZoom;
            } else {
                this.center = new LatLng(44, 16);
                this.zoom = 10;
            }
        }

        // Initialise custom map style
        var psMapStyle = new google.maps.StyledMapType(mapStyle, {name: "PocketSail"});
        var psMapTypeId = 'PocketSail';

        // Create google map
        this._map = new google.maps.Map(this.canvas.get(0), {
            zoom: this.zoom,
            maxZoom: 18,
            center: this.center.toGoogleLatLng(),
            panControl: false,
            streetViewControl: false,
            scaleControl: true,
            draggableCursor: this.cursor,
            disableDefaultUI: this.disableDefaultUI,
            mapTypeControlOptions: {
                mapTypeIds: [psMapTypeId, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID]
            },
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM,
                style: google.maps.ZoomControlStyle.SMALL
            }
        });

        // Set map to custom style
        this._map.mapTypes.set(psMapTypeId, psMapStyle);
        this._map.setMapTypeId(psMapTypeId);

        google.maps.event.addListener(this._map, 'idle', function () {

            if (!_this.init) {
                _this.init = true;
                callback && callback.call(_this);
            }

            if (_this.loadDataOnIdle) {
                _this.loadData(function (data) {
                    _this.handleData(data);
                    _this.redraw();
                });
                _this.loadDataOnIdle = false;
            }

            if (_this.cache) {
                var center = this.getCenter();
                set_cookie('psMapLat', center.lat());
                set_cookie('psMapLng', center.lng());
                set_cookie('psMapZoom', this.getZoom());
            }
        });

        google.maps.event.addListener(this._map, 'dragstart', function () {
            _this.ignoreMarkerClick = true;
        });

        google.maps.event.addListener(this._map, 'dragend', function () {
            _this.loadDataOnIdle = true;

        });

        google.maps.event.addListener(this._map, 'zoom_changed', function () {

            if (_this.loadDataOnZoomChange) {
                _this.loadData(function (data) {
                    _this.handleData(data);
                    _this.redraw();
                });
            } else {
                _this.loadDataOnZoomChange = true;
            }
        });

        google.maps.event.addListener(this._map, 'click', function (e) {

            if (_this.borderEdit) {
                _this._drawBorder(e);
                return;
            }

            var pos = {
                pixel: e.pixel,
                latLng: LatLng.fromGoogleLatLng(e.latLng)
            };

            if ('click' in _this.onCallbacks) {
                var callbacks = _this.onCallbacks.click;
                for (var i = 0; i < callbacks.length; i++) {
                    callbacks[i].call(_this, pos);
                }
            }
        });

        google.maps.event.addListener(this._map, 'rightclick', function (e) {

            var offset = _this.canvas.offset();
            var pos = {
                client: {
                    x: e.pixel.x + offset.left,
                    y: e.pixel.y + offset.top
                },
                pixel: e.pixel,
                latLng: LatLng.fromGoogleLatLng(e.latLng)
            };

            if ('rightclick' in _this.onCallbacks) {
                var callbacks = _this.onCallbacks.rightclick;
                for (var i = 0; i < callbacks.length; i++) {
                    callbacks[i].call(_this, pos);
                }
            }
        });

        google.maps.event.addListener(this._map, 'mousedown', function (e) {

            var offset = _this.canvas.offset();
            var pos = {
                client: {
                    x: e.pixel.x + offset.left,
                    y: e.pixel.y + offset.top
                },
                pixel: e.pixel,
                latLng: LatLng.fromGoogleLatLng(e.latLng)
            };

            if ('mousedown' in _this.onCallbacks) {
                var callbacks = _this.onCallbacks.mousedown;
                for (var i = 0; i < callbacks.length; i++) {
                    callbacks[i].call(_this, pos);
                }
            }
        });

        google.maps.event.addListener(this._map, 'mouseup', function (e) {
            if (_this.markerClicked) {
                _this.markerClicked = false;
            } else {
                _this.ignoreMarkerClick = false;
            }
        });
    };

    this.on = function (type, callback) {
        if (!(type in this.onCallbacks)) {
            this.onCallbacks[type] = [];
        }
        this.onCallbacks[type].push(callback);
    };

    this.trigger = function (type) {
        if (type in this.onCallbacks) {
            var callback = this.onCallbacks[type];
            for (var i = 0; i < callback.length; i++) {
                [].shift.call(arguments);
                callback[i].apply(this, arguments);
            }
        }
    };

    this._initBorderPolyline = function (latLng) {

        this.border.polyline = new google.maps.Polyline({
            map: _this._map,
            editable: true,
            clickable: true,
            strokeColor: 'darkblue',
            strokeWeight: 1,
            path: [latLng]
        });

        google.maps.event.addListener(this.border.polyline, 'rightclick', function (e) {
            if (e.vertex !== undefined) {
                this.getPath().removeAt(e.vertex);
            }
        });

        // Replace with polygon when click on first vertex
        google.maps.event.addListener(this.border.polyline, 'click', function (e) {
            if (e.vertex === 0) {
                _this._initBoderPolygon(this.getPath().getArray());
            }
        });
    };

    this._initBoderPolygon = function (array) {

        this.border.polygon = new google.maps.Polygon({
            map: _this._map,
            path: array,
            clickable: true,
            editable: true,
            strokeColor: 'darkblue',
            strokeWeight: 1,
            fillOpacity: 0.1
        });

        // Make polyline invisible
        if (this.border.polyline !== null) {
            this.border.polyline.setMap(null);
        }

        // Remove vertex when rightclicked
        google.maps.event.addListener(this.border.polygon, 'rightclick', function (e) {

            if (e.vertex !== undefined) {
                this.getPath().removeAt(e.vertex);

                // Replace polygon with polyline when only one vertex is left
                if (this.getPath().length === 1) {
                    _this._initBorderPolyline(this.getPath().getAt(0));
                    _this.border.polygon.setMap(null);
                    _this.border.polygon = null;
                }
            }
        });
    };

    this._drawBorder = function (e) {
        if (this.border.polyline === null && this.border.polygon === null) {
            this._initBorderPolyline(e.latLng);
        } else if (this.border.polygon === null) {
            var path = this.border.polyline.getPath();
            path.push(e.latLng);
        }
    };

    this.setBorderPolygon = function (polygon) {
        this._initBoderPolygon(polygon.toGoogleArray());
    };

    this.getBorderPolygon = function () {
        if (this.border.polygon === null) {
            return null;
        }
        return Polygon.fromGooglePolygon(this.border.polygon);
    };

    this.hasBorderPolygon = function () {
        return this.border.polygon !== null;
    };

    this.setDraggableMarkerLatLng = function (latLng) {
        if (!this.init) {
            throw "MapException: Cannot add draggable marker before google map has been initialized.";
        }
        this.draggableMarker = new google.maps.Marker({
            map: this._map,
            position: latLng.toGoogleLatLng(),
            draggable: true
        });
    };

    this.getDraggableMarkerLatLng = function () {
        if (this.draggableMarker === null) {
            return null;
        }
        return LatLng.fromGoogleLatLng(this.draggableMarker.getPosition());
    };

    this.hasDraggableMarker = function () {
        return this.draggableMarker !== null;
    };
}