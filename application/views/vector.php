<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/leaflet/leaflet.js"></script>
        <link rel="stylesheet" href="/application/js/leaflet/leaflet.css" />
        <style>
            #map { width: 600px; height: 400px; }
            .canvas { width: 600px; height: 400px; }
        </style>
        <script>
            $(function () {

                var Custom = L.Class.extend({
                    initialize: function () {
                        console.log('init');
                    },
                    onAdd: function (map) {
                        console.log('adding');
                        this._canvas = L.DomUtil.create('canvas', 'canvas');
                        this._canvas.width = 600;
                        this._canvas.height = 400;
                        map.getPanes().overlayPane.appendChild(this._canvas);
                        var ctx = this._canvas.getContext('2d');
                        ctx.fillRect(10, 10, 10, 10);
                        ctx.strokeRect(45, 100, 20, 10);
                        var canvas = this._canvas;
                        map.on('dragend', function(e) {
                            console.log(canvas.getBoundingClientRect());
                            console.log(map._mapPane.getBoundingClientRect());
                        });
                    }
                });

                var map = L.map('map');

                L.tileLayer('http://{s}.tiles.mapbox.com/v3/pocketsail.lkh1h348/{z}/{x}/{y}.png', {
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    maxZoom: 18
                }).addTo(map);

                
                map.setView([51.505, -0.09], 13);

                map.addLayer(new Custom());
            });
        </script>
    </head>
    <body>
        <div id="map"></div>
    </body>
</html>
