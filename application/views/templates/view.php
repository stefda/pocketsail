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

        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/brokers/TestBroker.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>

        <script>

            var poiId = <?= $poi->id ?>;
            var cat = '<?= $poi->cat ?>';   
            var sub = '<?= $poi->sub ?>';
            var latLng = LatLng.fromWKT('<?= $poi->latLng === null ? 'NULL' : $poi->latLng->toWKT() ?>');

            $(function() {

                var map = new Map({
                    canvas: 'canvas',
                    center: latLng,
                    zoom: 16,
                    poiId: poiId
                });

                $('#canvasResizeButton').click(function() {
                    var center = map.getCenter();
                    if ($('.tpl-canvas-wrapper').hasClass('tpl-canvas-wrapper-large')) {
                        $('.tpl-canvas-wrapper').removeClass('tpl-canvas-wrapper-large');
                        $('#canvas').width('280px');;
                    } else {
                        $('.tpl-canvas-wrapper').addClass('tpl-canvas-wrapper-large');
                        $('#canvas').width('100%');
                    }
                    google.maps.event.trigger(map.googleMap, 'resize');
                    map.setCenter(center);
                });
            });

        </script>

        <style>

            html, body { font-family: Arial; background-color: #fff; }
            body { overflow-y: scroll; font-size: 14px; background-color: #e6e7e8; }
            a, input, textarea, select { outline: none; font-family: Arial; display: inline-block; margin: 0; }
            h1 { font-size: 16px; margin: 0 0 7px 0; font-weight: bold; color: #555; }
            h2 { font-size: 15px; font-weight: bold; margin: 0 2px 7px 0; color: #333; }
            input { font-size: 13px; border: solid 1px #d0d1d2; padding: 5px 7px; }
            textarea { display: block; box-sizing: border-box; font-size: 13px; border: solid 1px #d0d1d2; padding: 5px 7px; line-height: 1.4em; }

            .tpl-section { width: 600px; margin-bottom: 20px; background-color: #f7f8f9; border-radius: 3px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); }
            .tpl-section-wrapper { padding: 10px 12px; line-height: 1.4em; }

            #canvas { box-shadow: 0 0 1px rgba(0, 0, 0, 0.2); }
            .tpl-canvas-wrapper { height: 200px; }
            .tpl-canvas-wrapper-large { height: 500px; }
            .tpl-canvas-resize-button { cursor: pointer; position: relative; top: -5px; left: 425px; background-color: #f7f8f9; border-radius: 3px; box-shadow: 0 0 3px rgba(0, 0, 0, 0.4); width: 49px; height: 10px; background-image: url('/application/layout/images/arrow-down.png'); background-repeat: no-repeat; background-position: 21px 3px; }
            .tpl-canvas-wrapper-large .tpl-canvas-resize-button { background-image: url('/application/layout/images/arrow-up.png'); }

        </style>

        <link type="text/css" rel="stylesheet" id="mapStyle" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div style="width: 100%; height: 45px; background-color: #f5f6f7; position: relative; z-index: 1;">
            <img src="/application/images/logo.png" style="margin: 7px 10px;" />
        </div>
        
        <div style="width: 900px; margin: 0 auto 20px auto; position: relative; z-index: 0;">

            <div class="tpl-canvas-wrapper" id="canvasWrapper">
                <div id="canvas" style="float: right; width: 280px; height: 100%;"></div>
                <div id="gallery" style="width: 600px; height: 200px; background-color: #d0d1d2;">
                </div>
                <div class="tpl-canvas-resize-button" id="canvasResizeButton"></div>
            </div>

            <div class="tpl-section" style="border-radius: 0 2px 2px 0;">
                <div class="tpl-section-wrapper">

                    <?= $poi->name ?>

                </div>
            </div>

            <? include_view_template($poi->sub, $poi->cat); ?>

        </div>

    </body>
</html>