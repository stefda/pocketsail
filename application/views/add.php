<html>
    <head>
        <title>Add marker</title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/controllers/Labeling.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script>

            $(function() {

                var map = null;

                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 14,
                    center: new google.maps.LatLng(43.869869, 15.323947),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    draggableCursor: 'default'
                });

                google.maps.event.addListener(map, 'click', function(event) {
                    //console.log(event.latLng)
                    $('#lat').val(event.latLng.lat());
                    $('#lng').val(event.latLng.lng());
                    var overlayProjection = map.getProjection();
                    var pos = overlayProjection.fromLatLngToPoint(event.latLng);
                    console.log(pos);
                });
                
                $('#button').click(function() {
                    //console.log($('#inputs input,select').serialize());
                    Labeling.save({post: $('#inputs input,select').serialize()});
                });
                
                console.log($('#metrics').width());

            });
        </script>
        <style>

        </style>
    </head>
    <body>

        <div id="map" style="width: 500px; height: 500px; float: left;"></div>

        <div id="inputs" style="margin-left: 500px; padding-left: 30px;">
            Lat: <input type="text" id="lat" name="lat" /><input type="text" id="y" /><br />
            Lng: <input type="text" id="lng" name="lng" /><input type="text" id="x" /><br />
            Name: <input type="text" id="name" name="name" /><br />
            Rank: <input type="text" id="rank" name="rank" /><br />
            Category: <select id="cat" name="cat">
                <option value="geo">Geofeature</option>
                <option value="admin">Administrative</option>
                <option value="berthing">Berthing</option>
                <option value="anchoring">Anchorage</option>
                <option value="goingout">Going out</option>
            </select><br />
            Sub-category: <select id="sub" name="sub">
                <option value="island">Island</option>
                <option value="cove">Cove</option>
                <option value="town">Town</option>
                <option value="marina">Marina</option>
                <option value="anchorage">Anchorage</option>
                <option value="restaurant">Restaurant</option>
            </select><br />
            <input type="button" id="button" value="Save" />
        </div>
        
        <div id="metrics" style="position: absolute; font-size: 12px; font-family: Arial;">Marina Zadar je zajebista</div>

    </body>
</html>



