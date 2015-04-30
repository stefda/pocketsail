Service = {
'place_info': function(poiID, type, options) {
var o = {};o.url = '/broker/call/service/place_info/'+encodeURIComponent(poiID)+'/'+encodeURIComponent(type)+'/?ajax&route';
o.type = 'GET';
o.async = true;
if (options !== undefined && options.exception !== undefined) {o.exception = options.exception;}
if (options !== undefined && options.beforeSend !== undefined) {o.beforeSend = options.beforeSend;}
if (options !== undefined && options.success !== undefined) {o.success = options.success;}
if (options !== undefined && options.post !== undefined) {o.data = options.post;}
return ajax(o);
}
}