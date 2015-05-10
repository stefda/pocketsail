function Marker(o) {

    this.psMap = o.map;
    this.map = o.map._map;
    this.label = o.label;
    this.url = o.label.url;
    this.id = this.label.id;
    this.text = o.label.text;
    this.latLng = this.label.latLng.toGoogleLatLng();
    this.desc = o.label.getDescriptor();

    this.div_ = null;
    this.setMap(this.map);

    this.getPoiId = function () {
        return this.id;
    };

    this.hasUrl = function () {
        return this.url !== undefined;
    };

    this.getPoiUrl = function () {
        return this.url;
    };
}

Marker.prototype = new google.maps.OverlayView();

Marker.prototype.onAdd = function () {

    this.div_ = document.createElement('div');
    this.div_.style.position = 'absolute';
    this.div_.className = 'label ' + this.label.sub;
    this.getPanes().floatPane.appendChild(this.div_);

    if (this.label.important) {
        if (this.label.hasText()) {
            this.div_.className = this.div_.className + " super";
        } else {
            this.div_.className = this.div_.className + " important";
        }
    }

    if (this.label.hasText()) {
        this.buildText();
    }

    if (this.label.hasBoundIcon() || this.label.hasFreeIcon()) {
        this.buildIcon();
    }

    // Closure
    var _this = this;

    google.maps.event.addDomListener(this.div_, 'contextmenu', function (e) {

        e.preventDefault();
        e.stopPropagation();

        var offset = _this.psMap.canvas.offset();
        var pos = {
            client: {
                x: e.clientX,
                y: e.clientY
            },
            pixel: {
                x: e.clientX - offset.left,
                y: e.clientY - offset.top
            }
        };

        if ('marker_contextmenu' in _this.psMap.onCallbacks) {
            var callbacks = _this.psMap.onCallbacks.marker_contextmenu;
            for (var i = 0; i < callbacks.length; i++) {
                callbacks[i].call(_this.map, _this, pos);
            }
        }
    });

    google.maps.event.addDomListener(this.div_, 'click', function (e) {

        e.preventDefault();
        e.stopPropagation();

        if (_this.psMap.ignoreMarkerClick) {
            _this.psMap.ignoreMarkerClick = false;
            return false;
        }

        var offset = _this.psMap.canvas.offset();
        var pos = {
            client: {
                x: e.clientX,
                y: e.clientY
            },
            pixel: {
                x: e.clientX - offset.left,
                y: e.clientY - offset.top
            }
        };

        if ('marker_click' in _this.psMap.onCallbacks) {
            var callbacks = _this.psMap.onCallbacks.marker_click;
            for (var i = 0; i < callbacks.length; i++) {
                callbacks[i].call(_this.map, _this, pos);
            }
        }
    });
    
    google.maps.event.addDomListener(this.div_, 'mouseup', function (e) {
        _this.psMap.markerClicked = true;
    });
};

Marker.prototype.draw = function () {
    var overlayProjection = this.getProjection();
    var pos = overlayProjection.fromLatLngToDivPixel(this.latLng);
    this.div_.style.top = pos.y + 'px';
    this.div_.style.left = pos.x + 'px';
};

Marker.prototype.onRemove = function () {
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
};

Marker.prototype.buildIcon = function () {

    var icon = document.createElement('div');
    var iconDesc = this.label.getIconStyle();

    var iw = iconDesc.getPixelWidth();
    var iw2 = Math.floor(iw / 2);
    var ilo = iconDesc.imageLeftOffset;
    var iro = iconDesc.imageRightOffset;

    icon.className = 'icon' + (this.label.hasFreeIcon() ? ' p' : '');
    icon.style.position = 'absolute';
    icon.style.width = iw + 'px';
    icon.style.height = iw + 'px';
    icon.style.top = -iw2 + 'px';
    icon.style.left = -iw2 + 'px';
    icon.style.backgroundPosition = ilo + 'px ' + iro + 'px';

    this.div_.appendChild(icon);
};

Marker.prototype.buildText = function () {

    var text = document.createElement('div');
    var textWrapper = null;

    var type = this.label.getFirstType();
    var th = this.desc.getTextPixelSize();
    var iw = this.desc.getBoundIconPixelWidth();
    var th2 = Math.floor(th / 2);
    var iw2 = Math.floor(iw / 2);
    var spc = this.desc.getSpacing();

    text.className = 'text';
    text.innerHTML = this.text;
    text.style.position = 'absolute';
    text.style.fontSize = th + 'px';
    text.style.lineHeight = th + 'px';
    text.style.fontWeight = this.desc.getTextWeight() === 'b' ? 'bold' : '';
    text.style.fontStyle = this.desc.getTextStyle() === 'i' ? 'italic' : '';

    if (type === 'X' || type === 'T' || type === 'B') {
        textWrapper = document.createElement('div');
        textWrapper.className = 'textWrapper';
        textWrapper.style.position = 'relative';
        textWrapper.appendChild(text);
        text.style.position = 'relative';
        text.style.left = '-50%';
    }
    else {
        textWrapper = text;
    }

    if (type === 'R' || type === 'L' || type === 'X') {
        textWrapper.style.top = -th2 + 'px';
    }

    switch (type) {
        case 'R':
            textWrapper.style.left = iw2 + spc + 'px';
            break;
        case 'L':
            textWrapper.style.right = iw2 + spc + 'px';
            break;
        case 'T':
            textWrapper.style.top = -iw2 - spc - th + 'px';
            break;
        case 'B':
            textWrapper.style.top = iw2 + spc + 'px';
            break;
    }
    this.div_.appendChild(textWrapper);
};