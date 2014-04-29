
function alert_error(errstr, errtype) {
    alert("There has been an error while processing your request.\n\n" + errstr);
}

function ajax(options) {

    var d = null;
    var o = {};

    o.url = options.url;
    o.type = options.type;
    o.beforeSend = options.beforeSend !== undefined ? options.beforeSend : null;
    o.data = options.data !== undefined ? options.data : {};
    o.async = options.async !== undefined ? options.async : true;

    o.success = function(result, textStatus) {

        if (options !== undefined && options.error !== undefined && result.type == 'error') {
            options.error(result.data.errstr, result.data.errtype);
        }

        if (result.type == 'error') {
            alert_error(result.data.errstr, result.data.errtype);
            return;
        }

        if (options !== undefined && options.success !== undefined) {
            var res = result.data !== undefined ? result.data.value : null;
            options.success(res);
            return;
        }

        if (result.data !== undefined) {
            d = result.data.value;
        }
        else {
            d = result;
        }
    }

    $.ajax(o);
    return d;
}