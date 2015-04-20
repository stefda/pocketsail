<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/geo/Proj.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/LatLngBounds.js"></script>
        <script src="/application/js/leaflet/leaflet.js"></script>
        <link type="text/css" rel="stylesheet" href="/application/js/leaflet/leaflet.css" />
        <style>
            #map { height: 600px; }
        </style>
        <script>
            
            $(function () {

                var map = L.map('map');

                map.on('load', function () {
                    console.log('asd');
                });
                
                map.on('contextmenu', function(e) {
                    console.log(e);
                });
                
                map.setView([51.505, -0.09], 13);

                L.tileLayer('http://{s}.tiles.mapbox.com/v3/pocketsail.lkh1h348/{z}/{x}/{y}.png', {
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    maxZoom: 18
                }).addTo(map);

                var A = new LatLng(50.710973, -0.789212);
                var B = new LatLng(51.392942, 1.473972);

                var bounds = new LatLngBounds(A, B);
                var width = $('#map').innerWidth();
                var height = $('#map').innerHeight();
                var zoom = bounds.getMaxZoom(width, height);

                L.polygon([
                    new L.latLng(A.lat, A.lng),
                    new L.latLng(B.lat, A.lng),
                    new L.latLng(B.lat, B.lng),
                    new L.latLng(A.lat, B.lng),
                    new L.latLng(A.lat, A.lng)
                ]).addTo(map);
                
                var marker = L.marker([50.5, 30.5], {draggable: true}).addTo(map);
                marker.on('contextmenu', function(e) {
                    console.log(e);
                });

                $('#click').click(function () {
                    var center = bounds.getCenter();
                    var latLng = L.latLng(center.lat, center.lng);
                    map.setView(latLng, zoom);
                });
            });
        </script>
    </head>
    <body>
        <div id="map"></div>
        <div><input type="button" id="click" value="Do" /></div>
    </body>
</html>
