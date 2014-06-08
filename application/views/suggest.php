<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>

        <script src="/application/js/brokers/APIBroker.js"></script>

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

        <script>

            $(function() {

                var map = new Map({
                    canvas: 'canvas',
                    center: new LatLng(43.866433, 15.309780),
                    zoom: 12
                });

                $('#clearSearchButton').click(function() {
                    map.setPoiId(0);
                    map.setTypes([]);
                    map.loadData();
                    $('#searchInput').val("");
                });

                var ac = $('#searchInput').autocomplete({
                    source: "/api/suggest",
                    appendTo: '#suggestList',
                    position: {my: "left top-1px"},
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

                        // Do call Map.loadData
                        console.log(poiId, types);

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

                    if (item.fulltext) {
                        return $('<li>')
                                .append('<a href="/test/fulltext?term=' + item.label + '" class="fulltextButton"><div>See more results for <i>' + item.label + '</i> &raquo;</div></a>')
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

        <link href="/application/layout/map.css" type="text/css" rel="stylesheet" />
        <link type="text/css" rel="stylesheet" href="/application/js/jquery/ui/custom-theme/jquery-ui.css" />

        <style>
            html, body { width: 100%; height: 100%; padding: 0; margin: 0; }

            .ui-autocomplete { border-color: #aaa; }
            .ui-autocomplete.ui-menu { padding: 0; font-family: Arial; font-size: 14px; opacity: 1; }
            .ui-autocomplete.ui-menu .ui-menu-item a { padding: 5px 7px; line-height: 1.2em; }
            .ui-autocomplete.ui-menu .ui-menu-item a.ui-state-focus { margin: 0; border: none; }
            .ui-autocomplete.ui-menu .ui-menu-item a.fulltextButton.ui-state-focus { margin: 0; border-top: solid 1px #aaa; }

            #searchInput { outline: none; font-size: 14px; padding: 5px 5px 5px 6px; font-family: Arial; }
            #suggestList { }

            #suggestList .name { font-size: 14px; white-space: nowrap; }
            #suggestList .name.type { padding: 6px 1px; }
            #suggestList .place { font-size: 11px; color: #999; display: inline-block; }
            #suggestList .sub { float: right; font-size: 10px; color: #aaa; padding-top: 2px; }

            a.fulltextButton { background-color: #f6f6f6; border-top: solid 1px #aaa; }
            a.fulltextButton div { padding: 3px 0; font-size: 11px; color: #666; font-weight: bold; text-align: center; }
        </style>

    </head>

    <body style="overflow: hidden;">

        <div id="canvas" style="width: 100%; height: 100%;"></div>

        <div style="position: absolute; top: 20px; left: 27px;">
            <input id="clearSearchButton" type="button" value="Clear" style="float: right;" />
            <input id="searchInput" type="text" style="width: 330px;" />
            <div id="suggestList"></div>
        </div>

    </body>
</html>