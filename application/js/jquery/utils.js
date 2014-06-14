
function n(name, type) {
    name = name.replace(/\]/g, '\\]');
    name = name.replace(/\[/g, '\\[');
    var selector = '[name=' + name + ']' + (type === undefined ? '' : (':' + type));
    return $(selector);
}

function Validator() {

    this.valFxs = [];

    /**
     * @param {Number} fx
     */
    this.add = function(fx) {
        this.valFxs.push(fx);
    };

    /**
     * @returns {boolean}
     */
    this.validate = function() {
        var allValid = true;
        for (var i = 0; i < this.valFxs.length; i++) {
            var valid = this.valFxs[i]();
            allValid = !allValid ? allValid : valid;
        }
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
    String.prototype.trim = function() {
        return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    };
}