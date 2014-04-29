<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Add POI</title>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/controllers/Test.js"></script>
        <script>
            
            var map;
            var action = '';
            var marker = null;
            var polyline = null;
            var polygon = null;
            
            $(function() {
                
                function add_vertex(latLng) {
                    if (polyline === null) {
                        polyline = new google.maps.Polyline({
                            map: map,
                            path: [latLng],
                            strokeColor: "#a90063",
                            strokeOpacity: 1.0,
                            strokeWeight: 2,
                            editable: true
                        });
                        google.maps.event.addDomListener(polyline, 'click', function(e) {
                            if (e.vertex === 0) {
                                polyline.setMap(null);
                                polygon = new google.maps.Polygon({
                                    map: map,
                                    paths: polyline.getPath().getArray(),
                                    strokeColor: "#a90063",
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: "#FFFFFF",
                                    fillOpacity: 0,
                                    editable: true
                                });
                                google.maps.event.addDomListener(polygon.getPath(), 'set_at', function(e) {
                                    set_boundary();
                                });
                                google.maps.event.addDomListener(polygon.getPath(), 'insert_at', function(e) {
                                    set_boundary();
                                });
                                set_boundary();
                            }
                        });
                    }
                    else {
                        polyline.getPath().push(latLng);
                    }
                }
                
                function set_boundary() {
                    var latLngArray = polygon.getPath().getArray();
                    var latLngStr = '';
                    for (var i = 0; i < latLngArray.length; i++) {
                        latLngStr += latLngArray[i].lat() + ' ' + latLngArray[i].lng() + ',';
                    }
                    latLngStr += latLngArray[0].lat() + ' ' + latLngArray[0].lng();
                    $('input[name=boundary]').val(latLngStr);
                    $('#boundaryOut').text('Done');
                }
                
                var mapOptions = {
                    zoom: 14,
                    center: new google.maps.LatLng(43.08731943912015, 16.70797348022461),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById('map'), mapOptions);
                
                $('#positionButton').click(function() {
                    action = 'position';
                });
                
                $('#boundaryButton').click(function() {
                    action = 'boundary';
                });
                
                $('#saveButton').click(function() {
                    //console.log($('#infoTable input,select,textarea').serialize());
                    Test.save_poi({
                        post: $('#infoTable input,select,textarea').serialize(),
                        success: function(r) {
                            location.reload();
//                            var ID = r.ID;
//                            var near = r.near;
//                            var country = r.country;
//                            for (var i = 0; i < near.length; i++) {
//                                $('#nearSelect').append('<option value="' + near[i].ID + '">' + near[i].name + '</option>');
//                            }
//                            for (var i = 0; i < country.length; i++) {
//                                $('#countrySelect').append('<option value="' + country[i].ID + '">' + country[i].name + '</option>');
//                            }
//                            $('#newID').val(ID);
//                            $('#infoTable').hide();
//                            $('#afterTable').show();
                        }
                    });
                });
                
                $('#updateButton').click(function() {
                    Test.update_poi({
                        post: $('#afterTable input,select').serialize(),
                        success: function(r) {
                            window.location.reload();
                        }
                    });
                });
                
                google.maps.event.addDomListener(map, 'click', function(e) {
                    if (action === 'position') {
                        if (marker === null) {
                            marker = new google.maps.Marker({
                                map: map,
                                position: e.latLng
                            });
                        }
                        else {
                            marker.setPosition(e.latLng);
                        }
                        var latLngStr = 'Point(' + e.latLng.lng() + ' ' + e.latLng.lat() + ')';
                        $('input[name=position]').val(latLngStr);
                        $('#positionOut').text('Done');
                    }
                    if (action === 'boundary') {
                        if (polygon === null) {
                            add_vertex(e.latLng);
                        }
                    }
                });
            });
            
        </script>
        <style>
            .right { text-align: right; vertical-align: top; }
            textarea { width: 300px; height: 70px; }
            #afterTable { display: none; }
        </style>
    </head>
    <body>
        <div id="map" style="background-color: #fff; height: 800px; position: absolute; right: 8px; left: 500px;">
        </div>
        <table id="infoTable" style="float: left;">
            <tr>
                <td class="right">
                    Name
                </td>
                <td>
                    <input type="text" name="name" />
                </td>
            </tr>
            <tr>
                <td class="right">
                    Category
                </td>
                <td>
                    <select name="cat">
                        <option value="geo">Geofeature</option>
                        <option value="admin">Administrative</option>
                        <option value="berthing">Berthing Facility</option>
                        <option value="anchoring">Anchoring and Mooring</option>
                        <option value="hazard">Hazard</option>
                        <option value="attraction">Attraction</option>
                        <option value="goingout">Going Out</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="right">
                    Subcategory
                </td>
                <td>
                    <select name="sub">
                        <option value="cove">Cove</option>
                        <option value="island">Island</option>
                        <option value="archipelago">Archipelago</option>
                        <option value="municipality">Municipality</option>
                        <option value="region">Region</option>
                        <option value="country">Country</option>
                        <option value="marina">Marina</option>
                        <option value="anchorage">Anchorage</option>
                        <option value="mooring">Mooring</option>
                        <option value="beach">Beach</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="bar">Bar</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="right">
                    Position
                </td>
                <td>
                    <input type="button" id="positionButton" value="select" />
                    <input type="hidden" name="position" value="" />
                    <span id="positionOut"></span>
                </td>
            </tr>
            <tr>
                <td class="right">
                    Boundary
                </td>
                <td>
                    <input type="button" id="boundaryButton" value="select" />
                    <input type="hidden" name="boundary" value="" />
                    <span id="boundaryOut"></span>
                </td>
            </tr>
            <tr>
                <td class="right">
                    Description
                </td>
                <td>
                    <textarea name="features[description][text]"></textarea>
                </td>
            </tr>
            <tr>
                <td class="right">
                </td>
                <td>
                    <input id="saveButton" type="button" value="Save" />
                </td>
            </tr>
        </table>

        <table id="afterTable" style="clear: both;">
            <input id="newID" type="hidden" name="ID" value="" />
            <tr>
                <td class="right">Near</td>
                <td>
                    <select id="nearSelect" name="nearID">
                        <option value="0">None</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="right">Country</td>
                <td>
                    <select id="countrySelect" name="countryID">
                        <option value="0">None</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input id="updateButton" type="button" value="Update" /></td>
            </tr>
        </table>
        
        <style>
            .waves { position: relative; clear: both; width: 100px; height: 100px; }
            .waves div { display: none; }
            .waves.N div.dirN { display: block; }
            .waves.NW div.dirNW { display: block; }
            .dirN { position: absolute; top: 0px; left: 0px; width: 100px; height: 100px; background-image: url('/application/images/wind-N.png'); background-repeat: no-repeat; background-position-x: 50px; }
            .dirNW { position: absolute; top: 0px; left: 0px; width: 100px; height: 100px; background-image: url('/application/images/wind-NW.png'); background-repeat: no-repeat; background-position-x: 27px; background-position-y: 9px; }
        </style>
        
        <div class="waves N NW">
            <div class="dirN"></div>
            <div class="dirNW"></div>
        </div>

    </body>
</html>