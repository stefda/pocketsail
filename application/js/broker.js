
function broker(options, origin) {

    var sync = undefined;
    var o = {};

    o.url = options.url;
    o.type = options.type;
    o.beforeSend = options.beforeSend !== undefined ? options.beforeSend : null;
    o.data = options.data !== undefined ? options.data : {};
    o.async = options.async !== undefined ? options.async : true;

    o.success = function (result) {

        if (options !== undefined && options.error !== undefined && result.type === 'error') {
            options.error(result.data.errstr, result.data.errtype);
        }

        if (result.status === 'error') {
            throw 'BrokerError: ' + result.message;
            return;
        }

        if (options !== undefined && options.success !== undefined) {
            var res = result.data !== undefined ? result.data.value : null;
            options.success(res);
            return;
        }

        if (result.data !== undefined) {
            sync = result.data.value;
        }
        else {
            sync = result;
        }
    };

    $.ajax(o);
    return sync;
}