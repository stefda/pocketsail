<!DOCTYPE html>
<html>

    <head>
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: 0; padding: 0 }
            #map { height: 100%; width: 100%; }
        </style>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    </script>

    <script type="text/javascript">
        $(function() {
            google.maps.visualRefresh = true;
            var mapOptions = {
                center: new google.maps.LatLng(43.87235932758666, 15.32300949096680),
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        });
    </script>

</head>

<body>
    <div id="map"/>
</body>

</html>