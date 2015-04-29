<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/geo2/Point.js"></script>
        <script src="/application/js/geo2/LatLng.js"></script>
        <script src="/application/js/geo2/Polygon.js"></script>
        <script src="/application/js/geo2/LatLngBounds.js"></script>
        <script src="/application/js/geo2/Proj.js"></script>
        <script>
            $(function () {
                var bounds = new LatLngBounds(new LatLng(0, 0), new LatLng(10, 10));
                console.log(bounds.getMaxZoom(256, 256));
            });
        </script>
    </head>
    <body>
    </body>
</html>
