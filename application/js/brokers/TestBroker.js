var TestBroker = function () {
};

TestBroker.post = function(options) {
var o = {};o.url = '/broker/call/test/post/?ajax&route';
o.type = 'POST';
o.async = true;
if (options !== undefined && options.exception !== undefined) {o.exception = options.exception;}
if (options !== undefined && options.beforeSend !== undefined) {o.beforeSend = options.beforeSend;}
if (options !== undefined && options.success !== undefined) {o.success = options.success;}
if (options !== undefined && options.post !== undefined) {o.data = options.post;}
return ajax(o);
};

