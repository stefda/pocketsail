<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/controllers/Test.js"></script>
        <script src="/application/js/geo/Geo.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LineString.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Bounds.js"></script>
        <script src="/application/js/geo/ViewBounds.js"></script>

        <script>

            $(function() {

//                console.log(Polygon.fromWKT("POLYGON(asd)"));

                var vb = ViewBounds.fromWKT("LINESTRING(190 40,170 30)");
                console.log(vb.toWKT());

//                var latLng = LatLng.fromWKT("POINT   (  17.234345    42.23434   )");
//                console.log(latLng.toWKT());

//                var map = new google.maps.Map(document.getElementById('canvas'), {
//                    zoom: 5,
//                    center: new google.maps.LatLng(0, 0)
//                });
                
//                var polyline = null;
//                
//                google.maps.event.addListener(map, 'idle', function(e) {
//                    var bounds = Bounds.fromGoogleBounds(this.getBounds());
//                    var center = LatLng.fromGoogleLatLng(this.getCenter());
//                    var zoom = this.getZoom();
//                    var trueCenter = Geo.trueCenter(center);
//                    var viewWidth = $('#canvas').width();
//                    var viewBounds = bounds.toViewBounds(zoom, trueCenter, viewWidth);
//                    console.log(viewBounds.toWKT());
//                    Test.compute({
//                        post: {
//                            bounds: viewBounds.toWKT()
//                        },
//                        success: function(res) {
//                            console.log(res);
////                            var bounds = Bounds.fromWKT(res);
////                            console.log(bounds);
//                        }
//                    });
//                });
            });

        </script>
        
        <style>
            html, body { width: 100%; height: 100%; }
        </style>
        
    </head>

    <body style="overflow: hidden;">

        <div id="canvas" style="width: 100%; height: 100%;"></div>

    </body>
</html>