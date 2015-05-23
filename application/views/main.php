<!DOCTYPE html>
<html>
    <head>

        <title>Pocketsail - The Modern Nautical Guide</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="icon" type="image/png"  href="/application/images/favicon4.png">

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/broker.js"></script>
        <script src="/application/js/utils.js"></script>

        <script src="/application/js/Map.js"></script>
        <script src="/application/js/MapStyle.js"></script>

        <script src="/application/js/geo/GeoJSON.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/LatLngBounds.js"></script>
        <script src="/application/js/geo/Proj.js"></script>
        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>
        <script src="/application/js/brokers/MapBroker.js"></script>

        <script src="/application/js/main.js"></script>
        <script src="/application/js/search.js"></script>

        <link type="text/css" rel="stylesheet" href="/application/layout/global.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/main.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" href="/application/js/jquery/ui/custom-theme/jquery-ui.css" />

        <script>

            var map = null;

            $(function () {

                $('#test').click(function () {
                    map.setParam('poiId', 22);
                    //map.setParam('poiIds', [4, 5]);
                    map.setParam('types', ['gasstation']);
                    map.loadData('search', function (data) {
                        this.handleData(data);
                        this.redraw();
                    });
                });
            });

        </script>

        <style>
            .searchInput { box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.2); }
        </style>

    </head>

    <body oncontextmenu="return false;">

        <div id="canvas"></div>
        <div id="card"></div>
        <div id="zoomOut"></div>

        <div style="position: absolute; top: 200px; right: 100px;">
            <input type="button" id="test" value="Test" />
        </div>

        <div id="logoWrapper">
            <img id="logo" src="/application/images/logo-free.png" />
        </div>

        <div id="searchInputWrapper">
            <input id="searchInput" type="text" />
            <div id="suggestList"></div>
        </div>

        <?= include_view('addPoiMenu') ?>
        <?= include_view('editPoiMenu') ?>

    </body>
</html>
