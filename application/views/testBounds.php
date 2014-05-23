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

                var map = new google.maps.Map(document.getElementById('canvas'), {
                    zoom: 5,
                    center: new google.maps.LatLng(43.173224108787, 16.790027618408),
                    draggableCursor: 'crosshair'
                });
                
                google.maps.event.addListener(map, 'click', function(e) {
                    console.log(e.latLng.lat());
                });

                google.maps.event.addListener(map, 'idle', function(e) {
                    var zoom = this.getZoom();
                    var center = LatLng.fromGoogleLatLng(this.getCenter());
                    var truCenter = Geo.trueCenter(center);
                    var viewWidth = $('#canvas').width();
                    var bounds = Bounds.fromGoogleBounds(this.getBounds());
                    
                    var viewBounds = bounds.toViewBounds(zoom, truCenter, viewWidth);
                    console.log(viewBounds.toWKT());
                });
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