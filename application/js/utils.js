
function parseHashParams() {

    var params = {};
    var hash = window.location.hash;
    var parts = hash.split(',');
    
    if (parts.length === 0 || parts[0] === '') {
        return null;
    }

    // If hash has POI reference
    if (parts.length > 0 && parts[0] !== '#') {
        var ref = parts[0].substring(1, parts[0].length);
        if (isNaN(parseInt(ref))) {
            params.poiUrl = ref;
        } else {
            params.poiId = parseInt(ref);
        }
    }

    // If hash has bounds
    if (parts.length > 4) {

        var swLat = parseFloat(parts[1]);
        var swLng = parseFloat(parts[2]);
        var neLat = parseFloat(parts[3]);
        var neLng = parseFloat(parts[4]);

        if (!(isNaN(swLat) || isNaN(swLng) || isNaN(neLat) || isNaN(neLng))) {
            var sw = new LatLng(swLat, swLng);
            var ne = new LatLng(neLat, neLng);
            params.bounds = new LatLngBounds(sw, ne);
        }
    }

    // If hash has zoom
    if (parts.length > 5) {
        var zoom = parseInt(parts[5]);
        if (!isNaN(zoom)) {
            params.zoom = zoom;
        }
    }

    // If hash has types
    if (parts.length > 6) {
        params.types = parts.slice(6, parts.length);
    }

    return params;
}

function hashHasParams() {
    return window.location.hash.length > 1;
}

function n(name, type) {
    name = name.replace(/\]/g, '\\]');
    name = name.replace(/\[/g, '\\[');
    var selector = '[name=' + name + ']' + (type === undefined ? '' : (':' + type));
    return $(selector);
}

function Validator() {

    this.valFxs = [];
    this.valid = true;

    /**
     * @param {Number} fx
     */
    this.add = function (fx) {
        this.valFxs.push(fx);
    };

    /**
     * @returns {boolean}
     */
    this.validate = function () {
        var allValid = true;
        for (var i = 0; i < this.valFxs.length; i++) {
            var valid = this.valFxs[i].call(this);
            allValid = !allValid ? allValid : valid;
        }
        this.valid = true;
        return allValid;
    };
}

function set_cookie(name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays === null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = name + "=" + c_value + ';path=/';
}

function get_cookie(name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x === name) {
            return unescape(y);
        }
    }
    return false;
}

function pad(str, max) {
    str += '';
    return str.length < max ? pad('0' + str, max) : str;
}

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    };
}