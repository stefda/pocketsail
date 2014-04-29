
// Constants & flags
var rightClickEvent = null;
var focusedMarker = null;
var map = null;
var styledMap = null;
var addMapManager = null;
var mapManager = null;

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
    $('#markerMenu').css('left', left + 'px');
    $('#markerMenu').css('top', top + 'px');
    $('#markerMenu').show();
}

function closeMarkerMenu() {
    $('#markerMenu').hide();
}

function openAddPoiDialog(html) {
    $('#addPoiDialog').show();
    $('#addPoiDialog').html(html);
    $('#name').focus();
}

function closeAddPoiDialog() {
    $('#addPoiDialog').html('');
    $('#addPoiDialog').hide();
    window.onbeforeunload = null;
    addMapManager = null;
    addMap = null;
    boundary = null;
    polyline = null;
    mapManager.forceIdle();
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

    $('#zoomOut').html(userZoom);

    userLat = userLat === undefined ? 44.13 : parseFloat(userLat);
    userLng = userLng === undefined ? 16.51 : parseFloat(userLng);
    userZoom = userZoom === undefined ? 5 : parseInt(userZoom);

    google.maps.visualRefresh = true;

    styledMap = new google.maps.StyledMapType(psMapStyles, {name: "PocketSail"});
    map = new google.maps.Map(document.getElementById('canvas'), {
        zoom: userZoom,
        center: new google.maps.LatLng(userLat, userLng),
        panControl: false,
        streetViewControl: false,
        maxZoom: 18,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM,
            style: google.maps.ZoomControlStyle.SMALL
        },
        mapTypeControlOptions: {
            mapTypeIds: ['map_style', google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE]
        },
        draggableCursor: 'crosshair'
    });
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');

    google.maps.event.addListener(map, 'zoom_changed', function(e) {
        $('#zoomOut').html(this.getZoom());
    });

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
    mapManager = new MapManager(map);

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
        var latLng = new LatLng(lat, lng);
        var cat = $(this).attr('poiCat');
        var sub = $(this).attr('poiSub');
        closeAddPoiMenu();
        Admin.get_add_poi_dialog({
            post: {latLng: latLng, cat: cat, sub: sub},
            success: function(res) {
                openAddPoiDialog(res);
            }
        });
        return false;
    });

    $('#markerMenu a').click(function(e) {
        e.preventDefault();
    });

    $('#markerMenu a').mousedown(function(e) {
        var poiId = focusedMarker.id;
        closeMarkerMenu();
        Admin.get_edit_poi_dialog({
            post: {id: poiId},
            success: function(res) {
                openAddPoiDialog(res);
            }
        });
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

    $('#labellingButton').click(function() {
        $(this).attr('disabled', true);
        Admin.label({
            success: function(res) {
                alert('Done');
                $('#labellingButton').attr('disabled', false);
                mapManager.forceIdle();
            }
        });
    });
});