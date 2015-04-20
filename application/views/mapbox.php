<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8' />
        <title></title>
        <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
        <script src='/application/js/mapbox/mapbox-gl-dev.js'></script>
        <link href='/application/js/mapbox/mapbox-gl.css' rel='stylesheet' />
        <style>
            body { margin:0; padding:0; }
            #map { position:absolute; top:0; bottom:0; width:100%; }
        </style>
    </head>
    <body>

        <div id='map'></div>
        <script>
            mapboxgl.accessToken = 'pk.eyJ1IjoicG9ja2V0c2FpbCIsImEiOiJ4dUxteVZ3In0.o-fDb2AuUMyNU7rndR9cSA';
            var map = new mapboxgl.Map({
                container: 'map', // container id
                style: 'https://www.mapbox.com/mapbox-gl-styles/styles/outdoors-v4.json', //stylesheet location
                center: [40, -74.50], // starting position
                zoom: 9 // starting zoom
            });
        </script>

    </body>
</html>