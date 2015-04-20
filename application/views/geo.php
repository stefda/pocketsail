<!DOCTYPE html>
<html>
    <head>
        <title>Geo testing</title>
        <meta charset="utf-8">
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/LatLngBounds.js"></script>
        <script src="/application/js/geo/Proj.js"></script>
        <script>
            $(function() {
                
                var bounds = new LatLngBounds(new LatLng(-85, -180), new LatLng(85, 180));
                
                console.log(bounds.getMaxZoom(1024, 1024));
                
//                var bounds = new LatLngBounds(new LatLng(0, 0), new LatLng(10, 10));
//                console.log(bounds.toPolygon().toGeoJson());

//                console.log(Proj.latlng2merc(new LatLng(85, 180)));
//                console.log(Proj.merc2latLng(new Point(Proj.XLIM, Proj.YLIM)));
            });
        </script>
    </head>
    <body>
        
    </body>
</html>
