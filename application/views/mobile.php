<!DOCTYPE html>
<html>
    <head>
        <title>PocketSail mobile</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script type="text/javascript" src="/application/js/jquery/jquery.js"></script>
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
        <script type="text/javascript">
            $(function() {

//                var map = new google.maps.Map(document.getElementById('canvas'), {
//                    zoom: 10,
//                    center: new google.maps.LatLng(44, 16.5),
//                    panControl: false,
//                    streetViewControl: false,
//                    scaleControl: true
//                });
//
//                google.maps.event.addListener(map, 'idle', function(e) {
//                    console.log('idle');
//                });
//
//                google.maps.event.addListener(map, 'dragstart', function(e) {
//                    console.log('dragstart');
//                });
//
//                google.maps.event.addListener(map, 'dragend', function(e) {
//                    console.log('dragend');
//                });
//                
//                google.maps.event.addListener(map, 'drag', function(e) {
//                    console.log('drag');
//                });

                var map = new Map({
                    canvas: 'canvas',
                    cursor: 'crosshair',
                    cache: true
                });
            });
        </script>
        <link type="text/css" rel="stylesheet" href="/application/layout/map-high.css" />
        <style type="text/css">
            html, body { width: 100%; height: 100%; }
            body { padding: 0; margin: 0; }
        </style>
    </head>
    <body>
        <div id="canvas" style="width: 100%; height: 100%;"></div>
    </body>
</html>