
var ignoreHashChange = false;

$(function () {

    window.onhashchange = function () {
        if (!ignoreHashChange) {
            var params = parseHashParams();
            map.clearAllParams();
            map.setParams(params);
            map.loadData('hash', function (data) {
                this.handleData(data);
                this.reload();
            });
        } else {
            ignoreHashChange = false;
        }
    };

    Array.prototype.inter = function (a) {
        return this.filter(function (i) {
            return a.indexOf(i) >= 0;
        });
    };

    Array.prototype.diff = function (a) {
        return this.filter(function (i) {
            return a.indexOf(i) < 0;
        });
    };

    /**
     * Define UI elements
     */

    var editMenu = $('#editMenu');
    var addMenu = $('#addMenu');
    var card = $('#card');
    var searchInput = $('#searchInput');

    /**
     * Create map
     */

    map = new Map($('#canvas'), {
        poiEditable: true
    });

    /**
     * Register event handlers
     */

    map.on('marker_click', function (marker) {

        var poiUrl = marker.url;
        var poiId = marker.getPoiId();

        ignoreHashChange = true;

        if (poiUrl !== '') {
            //window.location.hash = poiUrl + ',,,,,' + map.getZoom();
            window.location.hash = poiUrl;
        } else {
            //window.location.hash = poiId + ',,,,,' + map.getZoom();
            window.location.hash = poiId;
        }

        map.clearAllParams();
        map.setParam('poiId', poiId);
        map.loadData('click', function (data) {
            this.handleData(data);
            this.redraw();
        });
    });

    map.on('marker_contextmenu', function (marker, pos) {

        $('#editMenu').mapmenu({
            top: pos.client.y,
            left: pos.client.x,
            select: function (event, ui) {
                if (ui.item.value === 'edit') {
                    window.location = '/poi/edit?poiId=' + marker.id;
                } else {
                    $('#editMenu').hide();
                    alert('Not implemented!');
                }
            }
        });
    });

    map.on('click', function () {

        this.clearCardHtml();

        editMenu.hide();
        addMenu.hide();
        //card.hide();
        card.slideUp(150);
        searchInput.val('');

        // Clean-up and reload labels if the map is 'dirty'
        if (!this.paramsAreClear()) {
            this.clearAllParams();
            this.reload();
        }
    });

    map.on('mousedown', function () {
        editMenu.hide();
        addMenu.hide();
    });

    map.on('rightclick', function (pos) {

        addMenu.mapmenu({
            top: pos.client.y,
            left: pos.client.x,
            select: function (e, ui) {
                var sub = ui.item.value;
                if (sub !== undefined) {
                    addMenu.mapmenu('hide');
                    var lat = pos.latLng.lat();
                    var lng = pos.latLng.lng();
                    window.location = '/poi/add?sub=' + sub + '&lat=' + lat + '&lng=' + lng;
                }
            }
        });
    });

    map.on('card_changed', function (cardHtml) {
        card.html(cardHtml);
        card.show();
    });

    var params = parseHashParams();

    if (params) {
        map.setParams(params);
        map.loadData('hash', function (data) {
            this.handleData(data);
            this.initCanvas(function () {
                this.redraw();
            });
        });
    } else {
        map.initCanvas(function () {
            this.loadData(function (data) {
                this.handleData(data);
                this.redraw();
            });
        });
    }
});