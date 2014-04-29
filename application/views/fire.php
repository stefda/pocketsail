<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/map/style.js"></script>
        <style>
            html, body { width: 100%; height: 100%; }
        </style>
        <script>

            $(function() {

                function Marker(o) {

                    this.map = o.map;
                    this.latLng = o.position;

                    this.div_ = null;
//                    this.setMap(this.map);
                }

                Marker.prototype = new google.maps.OverlayView();

                Marker.prototype.onAdd = function() {
//                    this.div_ = document.createElement('div');
//                    this.div_.style.position = 'absolute';
//                    this.div_.style.width = '10px';
//                    this.div_.style.height = '10px';
//                    this.div_.style.backgroundColor = 'red';
//                    this.getPanes().floatPane.appendChild(this.div_);
                };

                Marker.prototype.draw = function() {
//                    var overlayProjection = this.getProjection();
//                    var pos = overlayProjection.fromLatLngToDivPixel(this.latLng);
//                    this.div_.style.top = pos.y + 'px';
//                    this.div_.style.left = pos.x + 'px';
                };

                Marker.prototype.onRemove = function() {
                    this.div_.parentNode.removeChild(this.div_);
                    this.div_ = null;
                };

                var styledMap = new google.maps.StyledMapType(psMapStyles, {name: "PocketSail"});
                var map = new google.maps.Map(document.getElementById('canvas'), {
                    zoom: 3,
                    center: new google.maps.LatLng(43.13, 16.5),
                    mapTypeControl: false,
                    panControl: false,
                    streetViewControl: false,
                    maxZoom: 18,
                    zoomControlOptions: {
                        position: google.maps.ControlPosition.RIGHT_BOTTOM,
                        style: google.maps.ZoomControlStyle.SMALL
                    }
                });
                map.mapTypes.set('map_style', styledMap);
                map.setMapTypeId('map_style');
                for (var i = 0; i < 500; i++) {
                    var lat = Math.random() * 180 - 90;
                    var lng = Math.random() * 360 - 180;
//                    new Marker({
//                        map: map,
//                        position: new google.maps.LatLng(lat, lng)
//                    });
                    new google.maps.Marker({
                        map: map,
                        position: new google.maps.LatLng(lat, lng),
                        clickabe: true,
                        draggable: true
                    });
                }
            });

        </script>
    </head>

    <body>
        <div id="canvas" style="width: 100%; height: 100%;"></div>
    </body>
</html>