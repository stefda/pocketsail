<!DOCTYPE html>
<html>
    <head>

        <title>Pocketsail - add</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="icon" type="image/png"  href="/application/images/favicon4.png">

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/broker.js"></script>
        <script src="/application/js/utils.js"></script>
        <script src="/application/js/add.js"></script>
        <script src="/application/js/ui.js"></script>

        <script src="/application/js/brokers/APIBroker.js"></script>
        <script src="/application/js/brokers/MapBroker.js"></script>
        <script src="/application/js/brokers/POIBroker.js"></script>

        <script src="/application/js/geo/GeoJSON.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/LatLngBounds.js"></script>
        <script src="/application/js/geo/Proj.js"></script>
        <script src="/application/js/Map.js"></script>
        <script src="/application/js/MapStyle.js"></script>

        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>

        <script>

            var map;
            var validator = new Validator();
            var cat = '<?= $poi->cat ?>';
            var sub = '<?= $poi->sub ?>';
            var center = LatLng.fromGeoJson(<?= $poi->latLng->js() ?>);

            $(function () {

                /**
                 * Define UI elements
                 */

                var canvas = $('#canvas');

                /**
                 * Initialize map
                 */

                map = new Map(canvas, {
                    center: center,
                    zoom: 15,
                    borderEdit: true
                });

                map.initCanvas(function () {
                    map.setDraggableMarkerLatLng(center);
                });

                initUI();
            });

        </script>

        <link type="text/css" rel="stylesheet" href="/application/layout/global.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />

        <style>
            #canvas { width: 100%; height: 100%; }
        </style>

    </head>

    <body>

        <div class="wrapper">

            <div id="header">

                <div style="float: right; margin: 15px 20px 0 0;">
                    <input id="saveButton" class="tpl-button tpl-button-blue" type="button" value="Save POI" />
                    <input id="cancelButton" class="tpl-button" type="button" value="Cancel" style="margin-left: 10px;" />
                </div>

                <div style="margin: 12px 0 0 20px;">
                    <img src="/application/images/logo.png"/>
                </div>

            </div>

            <div id="content">

                <div id="boxheadWrapper">
                    <div id="boxhead">

                        <div id="canvasWrapper">
                            <div id="canvasResizeButton"></div>
                            <div id="canvas"></div>
                        </div>

                        <div id="gallery" class="tpl-gallery">
                        </div>

                    </div>
                </div>

                <div>
                    <?= include_edit_template($poi->cat, $poi->sub) ?>
                </div>
                
                <div style="clear: both;"></div>

            </div>

            <div id="footer">
                <div style="width: 230px; margin: 16px auto 0; font-size: 12px; color: #919293;">
                    Pocketsail &copy; 2015, with <img src="/application/images/love.png" style="vertical-align: bottom;"/> from London.
                </div>
            </div>

        </div>

    </body>
</html>