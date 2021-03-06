<!DOCTYPE html>
<html>
    <head>
        <title>Menu</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/jquery/utils.js"></script>
        <script src="/application/js/brokers/APIBroker.js"></script>
        <script src="/application/js/brokers/AdminBroker.js"></script>
        <script src="/application/js/geo/Geo.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LineString.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Bounds.js"></script>
        <script src="/application/js/geo/ViewBounds.js"></script>
        <script src="/application/js/Map.js"></script>
        <script src="/application/js/MapStyle.js"></script>
        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>
        <script src="/application/js/geo/Projector.js"></script>
        <script src="/application/js/geo/Position.js"></script>

        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <link type="text/css" rel="stylesheet" href="/application/js/jquery/ui/custom-theme/jquery-ui.css" />

        <style>
            body, html { width: 100%; height: 100%; }
            body { margin: 0; padding: 0; }
            .loader { background-color: #f0f1f2 !important; border-color: #e0e1e2 !important; color: #d0d1d2 !important; }
            .searchInput { border: solid 1px #d0d1d2; font-family: Arial; font-size: 16px; padding: 5px 7px; width: 400px; box-sizing: border-box; }
            .ui-autocomplete { border-color: #aaa; }
            .ui-autocomplete.ui-menu { padding: 0; font-family: Arial; font-size: 14px; opacity: 1; }
            .ui-autocomplete.ui-menu .ui-menu-item a { padding: 5px 7px; line-height: 1.2em; }
            .ui-autocomplete.ui-menu .ui-menu-item a.ui-state-focus { margin: 0; border: none; }
            .ui-autocomplete.ui-menu .ui-menu-item a.fulltextButton.ui-state-focus { margin: 0; border-top: solid 1px #aaa; }
            #suggestList { }
            #suggestList .name { font-size: 14px; white-space: nowrap; }
            #suggestList .name.type { padding: 6px 1px; }
            #suggestList .place { font-size: 11px; color: #999; display: inline-block; }
            #suggestList .sub { float: right; font-size: 10px; color: #aaa; padding-top: 2px; }
            a.fulltextButton { background-color: #f6f6f6; border-top: solid 1px #aaa; }
            a.fulltextButton div { padding: 3px 0; font-size: 11px; color: #666; font-weight: bold; text-align: center; }
        </style>

        <script>

            var focusedMarker = null;

            $(function() {

                var map = new Map({
                    canvas: 'canvas',
                    cursor: 'crosshair',
                    cache: true
                });

                map.setFlags(['newPois']);

                map.addListener('rightclick', function(e) {

                    var latLng = LatLng.fromGoogleLatLng(e.latLng);
                    var canvasOffset = $('#canvas').offset();

                    $('#menu').mapmenu({
                        top: e.pixel.y + canvasOffset.top,
                        left: e.pixel.x + canvasOffset.left,
                        select: function(e, ui) {
                            var sub = ui.item.value;
                            if (sub !== undefined) {
                                $('#menu').mapmenu('hide');
                                var lat = latLng.lat;
                                var lng = latLng.lng;
                                window.location = '/poi/add?sub=' + sub + '&lat=' + lat + '&lng=' + lng;
                            }
                        }
                    });
                });

                map.addListener('zoom_changed', function(e) {
                    $('#zoomOut').text(this.getZoom());
                });

                map.addListener('click', function(e) {
                    $('.ps-ui-menu').mapmenu('hide');
                });

                map.addListener('drag', function(e) {
                    $('.ps-ui-menu').mapmenu('hide');
                });

                map.addListener('zoom_changed', function(e) {
                    $('.ps-ui-menu').mapmenu('hide');
                });

                $('#labellingButton').click(function() {
                    var button = $(this);
                    button.addClass('loader');
                    AdminBroker.label({
                        success: function(res) {
                            button.removeClass('loader');
                            window.location.reload();
                        }
                    });
                });

                $('#indexingButton').click(function() {
                    var button = $(this);
                    button.addClass('loader');
                    AdminBroker.index({
                        success: function(res) {
                            button.removeClass('loader');
                            window.location.reload();
                        }
                    });
                });

                $('#signoutButton').click(function() {
                    window.location = '/user/do_logout';
                });

                $('#clearButton').click(function() {
                    map.setPoiId(0);
                    map.setTypes([]);
                    map.loadData();
                    $('#searchInput').val("");
                });

                var ac = $('#searchInput').autocomplete({
                    source: "/api/suggest",
                    appendTo: '#suggestList',
                    position: {my: "left top-1px"},
                    // Response
                    response: function(event, ui) {
                        if (ui.content.length === 1) {
                            ui.content.unshift({
                                nores: true
                            });
                        }
                    },
                    // Define action on user select
                    select: function(event, ui) {

                        if (ui.item.fulltext) {
                            window.location = "/test/fulltext?term=" + ui.item.value;
                            return;
                        }

                        var poiBrief = ui.item.poi;
                        var types = ui.item.types;

                        var poiId = poiBrief === null ? 0 : poiBrief.id;
                        var types = types === null ? [] : types;

                        map.setTypes(types);
                        map.setPoiId(poiId);

                        APIBroker.loadData({
                            post: {
                                vBounds: map.getViewBounds().toWKT(),
                                zoom: map.getZoom(),
                                poiId: poiId,
                                types: types,
                                flags: ['panToPoi', 'zoomToTypes', 'poiInfo', 'poiCard']
                            },
                            success: function(res) {
                                map.handleResult(res);
                            }
                        });
                    }
                }).data("ui-autocomplete")._renderItem = renderItem;

                function renderItem(ul, item) {

                    if (item.nores) {
                        return $('<li>')
                                .append('<div style="text-align: center; padding: 11px 0; color: #d0d1d2;">No suggestions found</div>')
                                .appendTo(ul);
                    }

                    if (item.fulltext) {
                        return $('<li>')
                                .append('<a href="/test/fulltext?term=' + item.label + '" class="fulltextButton"><div>See fulltext search results for <i>' + item.label + '</i> &raquo;</div></a>')
                                .appendTo(ul);
                    }

                    var types = item.types;
                    var label = item.label;
                    var poi = item.poi;
                    var place = '';

                    if (poi !== null) {
                        place = (poi.nearName !== null ? poi.nearName + ', ' : '') + (poi.countryName !== null ? poi.countryName : '');
                    }

                    if (types !== null) {
                        if (poi !== null) {
                            return $('<li>')
                                    .append('<a><div class="sub">' + poi.subName + '</div><div class="name">' + label + '</div><div class="place">' + place + '</div></a>')
                                    .appendTo(ul);
                        }
                        else {
                            return $('<li>')
                                    .append('<a><div class="name type">' + label + '</div></a>')
                                    .appendTo(ul);
                        }
                    }
                    else {
                        if (poi !== null) {
                            return $('<li>')
                                    .append('<a><div class="sub">' + poi.subName + '</div><div class="name">' + label + '</div><div class="place">' + place + '</div></a>')
                                    .appendTo(ul);
                        }
                    }
                }
            });

        </script>
    </head>
    <body oncontextmenu="return false;">

        <div id="head" style="z-index: 9999; width: 100%; height: 60px; background-color: #e9eaeb; position: fixed; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
            <div style="float: right; margin: 15px 20px 0 0;">
                <input id="labellingButton" class="tpl-button tpl-button-blue" type="button" value="Do labelling" style="margin-left: 10px;" />
                <input id="indexingButton" class="tpl-button tpl-button-blue" type="button" value="Do indexing" style="margin-left: 10px;" />
                <input id="signoutButton" class="tpl-button" type="button" value="Sign out" style="margin-left: 10px;" />
            </div>
            <img src="/application/images/logo.png" style="float: left; margin: 14px 0 0 16px;" />
            <div style="position: relative; width: 400px; height: 30px; margin: 14px auto 0;">
                <div style="position: absolute; right: -50px;">
                    <input id="clearButton" type="button" value="Clear" />
                </div>
                <input id="searchInput" class="searchInput" type="text" />
                <div id="suggestList"></div>
            </div>
        </div>

        <div id="canvas" style="position: absolute; width: 100%; top: 60px; bottom: 0; height: auto;"></div>

        <div id="zoomOut" style="font-family: Arial; font-size: 14px; position: absolute; bottom: 22px; right: 40px; background-color: #fff; border: solid 1px #bbb; border-radius: 2px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); padding: 3px 5px;">10</div>

        <?= include_view('addPoiMenu') ?>
        <?= include_view('editPoiMenu') ?>

    </body>
</html>
