<!DOCTYPE html>
<html>
    <head>
        <title>PocketSail mobile</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script type="text/javascript" src="/application/js/jquery/jquery.js"></script>
        <script type="text/javascript">
            $(function() {
                var map = new google.maps.Map(document.getElementById('canvas'), {
                    zoom: 10,
                    center: new google.maps.LatLng(44, 16.5),
                    panControl: false,
                    streetViewControl: false,
                    scaleControl: true
                });
            });
        </script>
        <style type="text/css">
            html, body { width: 100%; height: 100%; }
            body { padding: 0; margin: 0; }
        </style>
    </head>
    <body>
        <div id="canvas" style="width: 100%; height: 100%;"></div>
    </body>
</html>