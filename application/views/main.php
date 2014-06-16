<!DOCTYPE html>
<html>
    <head>
        <title>Menu</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/jquery/utils.js"></script>
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
        
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

        <style>
            body, html { width: 100%; height: 100%; }
            body { margin: 0; padding: 0; }
        </style>

        <script>

            function openMarkerMenu(left, top) {
                console.log(left, top);
            }

            var focusedMarker = null;

            $(function() {

                var map = new Map({
                    canvas: 'canvas',
                    cursor: 'crosshair'
                });

                map.setFlags(['newPois']);

                map.addListener('rightclick', function(e) {

                    var latLng = LatLng.fromGoogleLatLng(e.latLng);

                    $('#menu').menu({
                        top: e.pixel.y,
                        left: e.pixel.x,
                        select: function(e, ui) {
                            var sub = ui.item.value;
                            if (sub !== undefined) {
                                $('#menu').menu('hide');
                                var lat = latLng.lat;
                                var lng = latLng.lng;
                                window.location = '/poi/add?sub=' + sub + '&lat=' + lat + '&lng=' + lng;
                            }
                        }
                    });
                });

                map.addListener('click', function(e) {
                    $('.ps-ui-menu').menu('hide');
                });

                map.addListener('drag', function(e) {
                    $('.ps-ui-menu').menu('hide');
                });

                map.addListener('zoom_changed', function(e) {
                    $('.ps-ui-menu').menu('hide');
                });
            });

        </script>
    </head>
    <body oncontextmenu="return false;">

        <div id="head" style="z-index: 9999; width: 100%; height: 60px; background-color: #e9eaeb; position: fixed; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
            <div style="float: right; margin: 15px 20px 0 0;">
                <input id="labellingButton" class="tpl-button tpl-button-blue" type="button" value="Do labelling" style="margin-left: 10px;" />
            </div>
            <img src="/application/images/logo.png" style="margin: 14px 0 0 16px;" />
        </div>

        <div id="canvas" style="position: absolute; width: 100%; top: 60px; bottom: 0;"></div>

        <?= include_view('addPoiMenu') ?>
        <?= include_view('editPoiMenu') ?>

    </body>
</html>
