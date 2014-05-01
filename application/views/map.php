<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <!--<script src="/application/js/search.js"></script>-->
        <script src="/application/js/controllers/API.js"></script>
        <script src="/application/js/controllers/Admin.js"></script>

        <script src="/application/js/map/style.js"></script>
        <script src="/application/js/map/MapManager.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>

        <script src = "/application/js/geo/Projector.js" ></script>
        <script src="/application/js/geo/Position.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Bounds.js"></script>

        <link type="text/css" rel="stylesheet" href="/application/js/jquery/ui/smoothness/jquery-ui-1.10.4.custom.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />

        <script>

            // Constants & flags
            var rightClickEvent = null;
            var focusedMarker = null;

            // Global functions
            function openAddPoiMenu(left, top) {
                closeMarkerMenu();
                $('#addPoiMenu').css('left', left + 'px');
                $('#addPoiMenu').css('top', top + 'px');
                $('#addPoiMenu').show();
            }

            function closeAddPoiMenu() {
                $('#addPoiMenu').hide();
            }

            function openMarkerMenu(left, top) {
                closeAddPoiMenu();
                $('#editPoiMenu').css('left', left + 'px');
                $('#editPoiMenu').css('top', top + 'px');
                $('#editPoiMenu').show();
            }

            function closeMarkerMenu() {
                $('#editPoiMenu').hide();
            }

            function openAddPoiDialog(html) {
                $('#addPoiDialog').html(html);
                $('#addPoiDialog').show();
                $('#name').focus();
            }

            function closeAddPoiDialog() {
                $('#addPoiDialog').html('');
                $('#addPoiDialog').hide();
                window.onbeforeunload = null;
            }

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires=" + d.toGMTString();
                document.cookie = cname + "=" + cvalue + "; " + expires;
            }

            function getCookie(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i].trim();
                    if (c.indexOf(name) === 0)
                        return c.substring(name.length, c.length);
                }
                return undefined;
            }

            $(function() {

                var userLat = getCookie('lat');
                var userLng = getCookie('lng');
                var userZoom = getCookie('zoom');

                userLat = userLat === undefined ? 44.13 : parseFloat(userLat);
                userLng = userLng === undefined ? 16.51 : parseFloat(userLng);
                userZoom = userZoom === undefined ? 5 : parseInt(userZoom);

                var styledMap = new google.maps.StyledMapType(psMapStyles, {name: "PocketSail"});
                var map = new google.maps.Map(document.getElementById('canvas'), {
                    zoom: userZoom,
                    center: new google.maps.LatLng(userLat, userLng),
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

                google.maps.event.addListener(map, 'drag', function(e) {
                    closeAddPoiMenu();
                    closeMarkerMenu();
                });

                google.maps.event.addListener(map, 'click', function(e) {
                    closeAddPoiMenu();
                    closeMarkerMenu();
                });

                google.maps.event.addListener(map, 'rightclick', function(e) {
                    rightClickEvent = e;
                    var left = e.pixel.x;
                    var top = e.pixel.y;
                    openAddPoiMenu(left, top);
                });

                // Init map manager
                var mapManager = new MapManager(map);

                mapManager.addListener('labels_loaded', function(res) {
                    if (res.poiInfo !== undefined) {
                        $('#shortInfoWrapper').html(res.poiInfo.html);
                        $('#shortInfoWrapper').show();
                    }
                });

                // Admin menus listeners
                $('#addPoiMenu a').mousedown(function(e) {
                    var lat = rightClickEvent.latLng.lat();
                    var lng = rightClickEvent.latLng.lng();
                    var cat = $(this).attr('poiCat');
                    var sub = $(this).attr('poiSub');
                    closeAddPoiMenu();
                    Admin.get_add_poi_dialog({
                        post: {lat: lat, lng: lng, cat: cat, sub: sub},
                        success: function(res) {
                            openAddPoiDialog(res);
                        }
                    });
                    return false;
                });

                $('#editPoiMenu a').click(function(e) {
                    e.preventDefault();
                });

                $('#editPoiMenu a').mousedown(function(e) {
                    var poiId = focusedMarker.id;
                    closeMarkerMenu();
                    Admin.get_edit_poi_dialog({
                        post: {id: poiId},
                        success: function(res) {
                            openAddPoiDialog(res);
                        }
                    });
                    return false;
                });

                $('#features input[type=checkbox]').click(function() {
                    mapManager.setTypes(get_selected_features());
                    mapManager.forceIdle();
                });

                // Highlight features
                $('#shortInfoWrapper a.closeShortInfo').on('click', function() {
                    $('#shortInfoWrapper').hide();
                    $('#shortInfoWrapper').html('');
                    mapManager.clearPoiId();
                    mapManager.forceIdle();
                    return false;
                });

                function get_selected_features() {
                    var features = [];
                    $('#features').find('input:checked').each(function() {
                        features.push($(this).val());
                    });
                    return features;
                }

                function clear_types() {
                    $('#features').find('input:checked').attr('checked', false);
                    mapManager.clearTypes();
                    mapManager.forceIdle();
                }

                $('#clearTypesButton').click(function() {
                    clear_types();
                });
            });

        </script>
    </head>

    <body oncontextmenu="return false;" style="overflow: hidden;">

        <div id="canvas" style="width: 100%; height: 100%;"></div>

        <div id="markerHoverInfo"></div>
        <div id="list" style="position: absolute;"></div>

        <!--
        <div id="searchWrapper" style="position: absolute; top: 10px; left: 10px;">
            <input type="text" id="searchInput" style="width: 300px; font-size: 16px; padding: 3px 5px;" />
        </div>
        -->

        <div id="features" style="position: absolute; top: 20px; right: 20px; background-color: #fff; padding: 20px;">
            <input type="checkbox" value="marina" />Marinas<br />
            <input type="checkbox" value="mooring" />Shore moorings<br />
            <input type="checkbox" value="anchorage" />Anchorages<br />
            <input type="checkbox" value="buoys" />Mooring buoys<br />
            <input type="checkbox" value="harbour" />Harbours<br />
            <input type="checkbox" value="restaurant" />Restaurants<br />
            <input type="checkbox" value="bar" />Bars<br />
            <input type="checkbox" value="gasstation" />Gas stations<br />
            <input id="clearTypesButton" type="button" value="Clear Types" />
        </div>

        <div id="textProbe" style="position: absolute; top: -20px; left: -1000px; font-family: Arial;"></div>

        <div id="addPoiDialog" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff;">
        </div>

        <?= include_view('admin/add_poi_menu') ?>
        <?= include_view('admin/marker_menu') ?>

    </body>
</html>