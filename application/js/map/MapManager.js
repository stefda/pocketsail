
var MapManager = function(map) {

    // Fields
    this.map = map;
    this.markers = {};
    this.newMarkers = [];
    this.lType = 'static';
    this.types = [];
    this.poiId = 0;
    this.poiInfo = null;


    // Flags
    this.zoomChanged = false;
    this.loadPoiInfo = false;

    // Closure
    var this_ = this;

    // Listeners
    this.listeners = {
        marker_click: [],
        marker_mouseover: [],
        marker_mousemove: [],
        marker_mouseout: [],
        labels_loaded: []
    };

    this.trigger = function(event, args) {
        if (this.listeners[event] === undefined) {
            return false;
        }
        for (var i = 0; i < this.listeners[event].length; i++) {
            this.listeners[event][i].apply(this, args);
        }
    };

    this.addListener = function(event, fx) {
        if (this.listeners[event] === undefined) {
            return false;
        }
        this.listeners[event].push(fx);
    };

    // Set google maps listeners
    google.maps.event.addListener(map, 'idle', function() {
        var zoom = this.getZoom();
        var center = this.getCenter();
        var bounds = Bounds.fromGoogleBounds(this.getBounds());
        this_.loadLabels(zoom, bounds);
        setCookie('lat', center.lat());
        setCookie('lng', center.lng());
        setCookie('zoom', zoom);
    });

    google.maps.event.addListener(map, 'zoom_changed', function() {
        this_.deleteMarkers();
        this_.zoomChanged = true;
    });

    this.ms = [];

    this.loadLabels = function(zoom, bbox) {
        API.get_labels({
            post: {zoom: zoom, bbox: bbox, types: this.types, poiId: this.poiId, loadPoiInfo: this.loadPoiInfo},
            success: function(res) {
                if (this_.newMarkers.length > 0) {
                    for (var i = 0; i < this_.newMarkers.length; i++) {
                        this_.newMarkers[i].setMap(null);
                    }
                }
                if (res.new !== undefined) {
                    var newPois = res.new;
                    for (var i = 0; i < newPois.length; i++) {
                        var poi = newPois[i];
                        var latLng = LatLng.deserialize(poi.latLng);
                        var newMarker = new google.maps.Marker({
                            map: this_.map,
                            position: latLng.toGoogleLatLng(),
                            icon: '/application/images/new-icon.png'
                        });
                        this_.newMarkers.push(newMarker);
                    }
                }
                var labels = [];
                for (var i = 0; i < res.labels.length; i++) {
                    labels.push(Label.deserialize(res.labels[i], zoom, res.lType));
                }
                if (res.lType === 'dynamic' || res.lType === 'poi') {
                    Labeller.doLabelling(labels);
                }
                if (res.poiInfo !== undefined) {
                    this_.poiInfo = res.poiInfo;
                }
                this_.printLabels(labels, res.lType);
                this_.trigger('labels_loaded', [res]);
                this_.loadPoiInfo = false;
            }
        });
    };

    this.printLabels = function(labels, lType) {
        if (this.lType === lType && !this.zoomChanged) {
            this.updateLabels(labels);
        }
        else {
            this.replaceLabels(labels);
            this.lType = lType;
            this.zoomChanged = false;
        }
    };

    this.updateLabels = function(labels) {

        var newMarkers = {};
        var exp = null;
        if (this.poiInfo !== null && this.poiInfo.exposition !== null) {
            exp = {
                wind: this.poiInfo.exposition.dir.wind,
                waves: this.poiInfo.exposition.dir.waves
            };
        }

        //for (var i = 0; i < labels.length; i++) {
        for (var i = labels.length - 1; i >= 0; i--) {
            var l = labels[i];
            if (this.markers[l.id] === undefined
                    || this.markers[l.id].desc !== l.desc) {
                if (this.markers[l.id] !== undefined) {
                    this.markers[l.id].setMap(null);
                }
                var marker = new Marker({
                    mapManager: this_,
                    label: labels[i],
                    exp: l.id === this.poiId ? exp : null
                            //exp: l.id === this.poiId ? {wind: ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'], waves: []} : null
                });
                newMarkers[l.id] = marker;
            }
            else {
                newMarkers[l.id] = this.markers[l.id];
                delete this.markers[l.id];
            }
        }
        for (var key in this.markers) {
            this.markers[key].setMap(null);
        }
        this.markers = newMarkers;
    };

    this.replaceLabels = function(labels) {

        var newMarkers = {};
        var exp = null;
        if (this.poiInfo !== null && this.poiInfo.exposition !== null) {
            exp = {
                wind: this.poiInfo.exposition.dir.wind,
                waves: this.poiInfo.exposition.dir.waves
            };
        }

        //for (var i = 0; i < labels.length; i++) {
        for (var i = labels.length - 1; i >= 0; i--) {
            var l = labels[i];
            var marker = new Marker({
                mapManager: this_,
                label: labels[i],
                exp: l.id === this.poiId ? exp : null
                        //exp: l.id === this.poiId ? {wind: ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'], waves: []} : null
            });
            newMarkers[l.id] = marker;
        }
        this.deleteMarkers();
        this.markers = newMarkers;
    };

    this.deleteMarkers = function() {
        for (var key in this_.markers) {
            this_.markers[key].setMap(null);
        }
    };

    this.setTypes = function(types) {
        this.types = types;
    };

    this.setPoiId = function(poiId) {
        this.poiId = poiId;
        this.loadPoiInfo = true;
    };

    this.panTo = function(latLng, zoom) {
        if (this.map.getCenter().equals(latLng.toGoogleLatLng())
                && this.map.getZoom() === zoom) {
            google.maps.event.trigger(this.map, 'idle');
        }
        else {
            this.map.panTo(latLng.toGoogleLatLng());
            this.map.setZoom(zoom);
        }
    };

    this.fitBounds = function(bounds) {
        var startBounds = this.map.getBounds();
        this.map.fitBounds(bounds.toGoogleBounds());
        if (startBounds.equals(this.map.getBounds())) {
            this.forceIdle();
        }
    };

    this.getZoom = function() {
        return this.map.getZoom();
    };

    this.setZoom = function(zoom) {
        if (this.map.getZoom() === zoom) {
            this.forceIdle();
        }
        else {
            this.map.setZoom(zoom);
        }
    };

    this.getBounds = function() {
        return Bounds.fromGoogleBounds(this.map.getBounds());
    };

    this.forceIdle = function() {
        google.maps.event.trigger(this.map, 'idle');
    };

    this.clearTypes = function() {
        this.types = [];
    };

    this.clearPoiId = function() {
        this.poiId = 0;
    };
};