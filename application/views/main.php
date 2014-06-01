<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>-->
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>

        <script src="/application/js/controllers/MapBroker.js"></script>

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
        <!--<script src="/application/js/labelling/Marker.js"></script>-->
        <script src="/application/js/geo/Projector.js"></script>
        <script src="/application/js/geo/Position.js"></script>

        <script>

            $(function() {
                MapBroker.loadData({
                    post: {
                        vBounds: 'Bounds(-90 15, -95 20)',
                        zoom: 18,
//                        types: ['marina']
                        poiId: 1,
                        flags: ['poiInfo', 'panToPoi']
                    },
                    success: function(res) {
                        console.log(res);
                    }
                });
            });

        </script>

        <link href="/application/layout/map.css" type="text/css" rel="stylesheet" />
        <style>
            html, body { width: 100%; height: 100%; padding: 0; margin: 0; }
        </style>

    </head>

    <body style="overflow: hidden;">

        <div id="canvas" style="width: 100%; height: 100%;"></div>

    </body>
</html>