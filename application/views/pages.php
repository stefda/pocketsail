<!DOCTYPE html>
<html>
    <head>
        <title>Pages</title>
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
        <script src="/application/js/jquery/jquery.js"></script>

        <link type="text/css" rel="stylesheet" id="mapStyle" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

        <script>
            $(function() {

                var map = new Map({
                    canvas: 'canvas',
                    center: new LatLng(43, 16.5),
                    zoom: 10
                });

                var top = -95;
                var zIndex = 100;

                $.fn.isAfter = function(sel) {
                    return this.prevAll(sel).length !== 0;
                };

                $.fn.isBefore = function(sel) {
                    return this.nextAll(sel).length !== 0;
                };

                var names = [
                    'ACI Marina Palmižana', 'Hvar (Town), Sv.Klement', 'Gas statation Poreč', 'Anchorage Hiljaca', 'Anchorage Piškera'
                ];

                $('.page').each(function() {
                    $(this).css('top', top + 'px');
                    $(this).css('z-index', zIndex--);
                    top += 35;
                    var i = Math.floor(Math.random() * names.length);
                    //console.log(i);
                    $(this).find('.content').text(names[i]);
                });

                $('#pages').on('mouseenter', '.page:not(.selected)', function() {
                    $(this).animate({
                        top: "+=3px"
                    }, 100);
                });

                $('#pages').on('mouseleave', '.page:not(.selected)', function() {
                    $(this).animate({
                        top: "-=3px"
                    }, 100);
                });

                $('.page').click(function() {

                    if ($('.selected').length > 0) {
                        if ($(this).isAfter('.selected')) {
                            $('.selected').animate({
                                top: "-=140px"
                            }, 50);
                            $('.selected').nextUntil($(this)).animate({
                                top: "-=140px"
                            }, 50);
                            $('.selected').removeClass('selected');
                            $(this).animate({
                                top: "-=3px"
                            });
                            $(this).addClass('selected');
                        } else {
                            $(this).animate({
                                top: "+=135px"
                            }, 50);
                            $(this).nextUntil('.selected').animate({
                                top: "+=140px"
                            }, 50);
                            $('.selected').removeClass('selected');
                            $(this).addClass('selected');
                        }
                    } else {
                        $(this).addClass('selected');
                        $(this).animate({
                            top: "+=135px"
                        }, 50);
                        $(this).nextAll().animate({
                            top: "+=140px"
                        }, 50);
                    }
                });
            });
        </script>
        <style>
            html, body { width: 100%; height: 100%; margin: 0; padding: 0; font-family: Arial; font-size: 15px; color: #444; cursor: pointer; }
            .page { position: absolute; width: 350px; height: 175px; background-color: #fff; border-radius: 2px; box-shadow: 0 2px 3px #aaa; }
            .content { position: absolute; bottom: 8px; left: 13px; }
            .selected {  }
        </style>
    </head>
    <body>
        <div id="canvasWrapper" style="width: 100%; height: 100%; background-color: #e0e1e2; position: fixed;">
            <div id="canvas" style="width: 100%; height: 100%;"></div>
        </div>
        <div style="height: 45px; width: 100%; z-index: 999; background-color: #f0f1f2; position: fixed; z-index: 9999;">
            <img src="/application/images/logo.png" style="margin: 8px 0 0 15px;"/>
        </div>
        <div id="pages" style="width: 100%; height: 100%; background-color: #e0e1e2; overflow-y: hidden;">
            <div style="padding: 10px;">
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>
                <div class="page"><div class="content"></div></div>

            </div>
        </div>
    </body>
</html>
