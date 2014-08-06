<!DOCTYPE html>
<html>

    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>

        <style type="text/css">
            html, body { width: 100%; height: 100%; }
        </style>

        <script>

            $(function() {

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: new google.maps.LatLng(44, 17)
                });

                function CanvasLayer(map) {
                    this.div_ = null;
                    this.setMap(map);
                }

                CanvasLayer.prototype = new google.maps.OverlayView();

                CanvasLayer.prototype.onAdd = function() {

                    var mapDiv = this.getMap().getDiv();
                    this.div_ = document.createElement('canvas');

                    this.div_.style.position = 'absolute';
                    this.div_.style.top = 0;
                    this.div_.style.left = 0;
//                    this.div_.style.pointerEvents = 'none';
                    this.div_.width = mapDiv.offsetWidth;
                    this.div_.height = mapDiv.offsetHeight;

                    this.getPanes().floatPane.appendChild(this.div_);
                };

                CanvasLayer.prototype.draw = function() {

                    var bounds = this.getMap().getBounds();
                    var topLeft = new google.maps.LatLng(bounds.getNorthEast().lat(), bounds.getSouthWest().lng());
                    var projection = this.getProjection();
                    var divTopLeft = projection.fromLatLngToDivPixel(topLeft);
                    this.div_.style.top = Math.round(divTopLeft.y) + 'px';
                    this.div_.style.left = Math.round(divTopLeft.x) + 'px';
                    
//                    this.div_.width = this.div_.width;
                    
                    var ctx = this.div_.getContext("2d");
                    for (var i = 0; i < 100; i++) {
                        var posx = Math.round(Math.random() * 500);
                        var posy = Math.round(Math.random() * 500);
                        ctx.font = "18px Arial";
                        ctx.lineWidth = 3;
                        ctx.strokeStyle = 'rgba(255, 255, 255, 0.7)';
                        ctx.strokeText('Palmižana', posx, posy);
                        ctx.fillStyle = '#333';
                        ctx.fillText('Palmižana', posx, posy);
                    }

                    console.log('draw');
                };

                var canvas = new CanvasLayer(map);
            });

        </script>

    </head>
    <body>

        <div id="map" style="width: 100%; height: 100%;"></div>

    </body>
</html>
