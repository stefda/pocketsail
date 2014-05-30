<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/controllers/TestBroker.js"></script>
        <script src="/application/js/controllers/MapBroker.js"></script>
        <script src="/application/js/geo/Geo.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LineString.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Bounds.js"></script>
        <script src="/application/js/geo/ViewBounds.js"></script>
        <script src="/application/js/Map.js"></script>

        <script>

            $(function() {

                var map = new Map('canvas', new LatLng(44, 17), 10);
                
                $('#button').click(function() {
                    MapBroker.loadData({
                        post: {
                            vBounds: map.getViewBounds().toWKT(),
                            zoom: map.getZoom(),
                            flags: ['panToPoi']
                        },
                        success: function(res) {
                            map.handleResult(res);
                        }
                    });
                });

//                $('.focusPoi').click(function() {
//                    var id = $(this).attr('poiId');
//                    var boundaryWKT = $(this).attr('boundaryWKT');
//                    var boundary = Polygon.fromWKT(boundaryWKT);
//                    var vb = ViewBounds.fromPolygon(boundary);
//                    //console.log(vb);
//
//                    var gBounds = map.getBounds();
//                    var zoom = map.getZoom();
//                    var center = LatLng.fromGoogleLatLng(map.getCenter());
//                    var trueCenter = Geo.trueCenter(center);
//                    var viewWidth = $('#canvas').width();
//
//                    var bounds = Bounds.fromGoogleBounds(gBounds);
//                    var vBounds = bounds.toViewBounds(zoom, trueCenter, viewWidth);
//
//                    TestBroker.compute({
//                        post: {
//                            zoom: zoom,
//                            vBounds: vBounds.toWKT(),
//                            id: id
//                        },
//                        success: function(res) {
//                            var zoom = res.zoom;
//                            var center = LatLng.fromWKT(res.center);
//                            console.log(zoom, center);
//                            map.setCenter(center.toGoogleLatLng());
//                            map.setZoom(zoom);
//                        }
//                    });
//                });
//
//                var map = new google.maps.Map(document.getElementById('canvas'), {
//                    zoom: 5,
//                    center: new google.maps.LatLng(43.173224108787, 16.790027618408),
//                    draggableCursor: 'crosshair'
//                });
//
//                google.maps.event.addListener(map, 'click', function(e) {
//                    console.log(e.latLng.lat());
//                });
//
//                google.maps.event.addListener(map, 'idle', function(e) {
//                    console.log(ViewBounds.fromMap(map));
//                });
            });

        </script>

        <style>
            html, body { width: 100%; height: 100%; padding: 0; margin: 0; }
        </style>

    </head>

    <body style="overflow: hidden;">

        <div style="float: right; height: 100px; font-size: 10px;">
            <input id="button" type="button" value="click" />
            <? foreach ($pois AS $poi): ?>
                <div class="focusPoi" poiId="<?= $poi['id'] ?>" boundaryWKT="<?= $poi['boundaryWKT'] ?>"><?= $poi['name']; ?></div>
            <? endforeach; ?>
        </div>
        
        <div id="canvas" style="width: 1200px; height: 100%;"></div>

    </body>
</html>