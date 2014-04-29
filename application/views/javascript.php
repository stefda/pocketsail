<!DOCTYPE html>
<html>
    <head>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/map/MapManager.js"></script>
        <script src="/application/js/map/POIMarker.js"></script>
        <script src="/application/js/map/map-style.js"></script>
        <script src="/application/js/controllers/MarkerBroker.js"></script>
        <script>

            $(function() {
                
                var styledMap = new google.maps.StyledMapType(psMapStyles, {name: "PocketSail"});
                
                var map = new google.maps.Map(document.getElementById('canvas'), {
                    zoom: 8,
                    center: new google.maps.LatLng(43.5, 17)
                });
                map.mapTypes.set('map_style', styledMap);
                map.setMapTypeId('map_style');
                
                var mm = MapManager({
                    map: map
                });
            });
        </script>
        <style>
            body, html { height: 100%; margin: 0; padding: 0; }
        </style>
        <style>

            html { height: 100% }
            body { height: 100%; margin: 0; padding: 0 }
            #map { height: 100% }

            .marker { position: absolute; }

            .marker .icon { position: absolute; cursor: pointer; cursor: hand; -webkit-filter: drop-shadow(0px 0px 1px #fff); filter: drop-shadow(0px 0px 1px #fff); background-image: url('/application/images/map-icons/all.png'); background-position-y: 0; }
            .marker.hidden .icon { background-position-y: -16px; }
            /* .marker .icon { position: absolute; cursor: pointer; cursor: hand; -webkit-filter: drop-shadow(0px 0px 1px #000); filter: drop-shadow(0px 0px 1px #000); } */

            .marker.large .icon { z-index: 99; width: 16px; height: 16px; top: -8px; left: -8px; }
            .marker.small .icon { z-index: 9; width: 6px; height: 6px; top: -3px; left: -3px; }

            .marker.large .icon.island { background-position-x: -80px; }
            .marker.small .icon.island { background-image: url('/application/images/map-icons/island-small.png'); }

            .marker.large .icon.restaurant { background-position-x: -64px; }
            .marker.small .icon.restaurant { background-image: url('/application/images/map-icons/restaurant-small.png'); }

            .marker.large .icon.municipality { background-position-x: -48px; }
            .marker.small .icon.municipality { background-image: url('/application/images/map-icons/town-small.png'); }

            .marker.large .icon.marina { background-position-x: -32px; }
            .marker.small .icon.marina { background-image: url('/application/images/map-icons/marina-small.png'); }

            .marker.large .icon.anchorage { background-position-x: -16px; }
            .marker.small .icon.anchorage { background-image: url('/application/images/map-icons/anchorage-small.png'); }

            .marker.large .icon.cove { background-position-x: 0; }
            .marker.small .icon.cove { background-image: url('/application/images/map-icons/cove-small.png'); }

            .marker.large .label.restaurant { font-style: italic; }

            .marker .label { position: absolute; z-index: 9999; cursor: pointer; cursor: hand; font-family: Arial; font-weight: bold; text-wrap: none; white-space: nowrap; color: #333; text-shadow: -1px -1px 0 rgba(255, 255, 255, 0.8), 1px -1px 0 rgba(255, 255, 255, 0.8), -1px 1px 0 rgba(255, 255, 255, 0.8), 1px 1px 0 rgba(255, 255, 255, 0.8); }
            .marker.hidden .label { color: #999; }
            /* .marker .label { position: absolute; z-index: 9999; cursor: pointer; cursor: hand; font-family: Arial; font-weight: bold; text-wrap: none; white-space: nowrap; color: #fff; text-shadow: -1px -1px 0 rgba(0, 05, 0, 0.8), 1px -1px 0 rgba(0, 0, 0, 0.8), -1px 1px 0 rgba(0, 0, 0, 0.8), 1px 1px 0 rgba(0, 0, 0, 0.8); } */

            .marker.large .label { top: -7px; font-size: 12px; }
            .marker.large .label.right { left: 12px; }
            .marker.large .label.left { right: 11px; }
            .marker.large .label.bottom { top: 10px; left: -20px; }
            .marker.large .label.top { top: -25px; left: -20px; }

        </style>
    </head>
    <body>
        <div id="canvas" style="width: 100%; height: 100%;">
        </div>
    </body>
</html>