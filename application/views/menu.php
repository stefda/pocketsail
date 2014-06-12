<!DOCTYPE html>
<html>
    <head>
        <title>Menu</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
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
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <style>
            body, html { width: 100%; height: 100%; }
            body { margin: 0; padding: 0; }
        </style>
        <script>
            $(function() {

                var map = new Map({
                    canvas: 'canvas',
                    center: new LatLng(43.866433, 15.309780),
                    zoom: 12,
                    cursor: 'crosshair'
                });

                var latLng = null;

                map.addListener('rightclick', function(e) {
                    latLng = LatLng.fromGoogleLatLng(e.latLng);
                    $('#menu').menu({
                        top: e.pixel.y,
                        left: e.pixel.x,
                        select: function(e, ui) {
                            var type = ui.item.type;
                            var val = ui.item.val;
                            if (type === 'sub') {
                                $('#menu').menu('hide');
                                var lat = latLng.lat;
                                var lng = latLng.lng;
                                latLng = null;
                                window.location = '/test/edit?cat=berthing&sub=marina&lat=' + lat + '&lng=' + lng;
                            }
                        }
                    });
                });
                
                map.addListener('click', function(e) {
                    $('#menu').menu('hide');
                });

                map.addListener('drag', function(e) {
                    $('#menu').menu('hide');
                });
            });
        </script>
    </head>
    <body oncontextmenu="return false;">

        <div id="canvas" style="width: 100%; height: 100%;"></div>

        <?= include_view('addPoiMenu') ?>

    </body>
</html>
