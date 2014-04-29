
$(function() {

    $('#searchInput').autocomplete({
        source: "/api/search",
        appendTo: "#list",
        select: function(event, ui) {
            switch (ui.item.action) {
                case 'types':
                    {
                        var types = ui.item.types;
                        var bounds = mapManager.getBounds();
                        API.get_mcz({
                            post: {
                                zoom: mapManager.map.getZoom(),
                                bounds: bounds.serialize(),
                                types: types
                            },
                            success: function(res) {
                                var zoom = res.zoom;
                                mapManager.setTypes(types);
                                mapManager.setZoom(zoom);
                            }
                        });
                        break;
                    }
                case 'near_point':
                    {
                        var poi = ui.item.poi;
                        var latLng = new LatLng(poi.lat, poi.lng);
                        var types = ui.item.types;
                        var bounds = mapManager.getBounds();
                        API.get_mcz({
                            post: {
                                zoom: mapManager.map.getZoom(),
                                bounds: bounds.serialize(),
                                poiLatLng: latLng.serialize(),
                                types: types
                            },
                            success: function(res) {
                                var latLng = LatLng.deserialize(res.latLng);
                                var zoom = res.zoom;
                                mapManager.setPoiId(poi.id);
                                mapManager.setTypes(types);
                                mapManager.panTo(latLng, zoom);
                            }
                        });
                        break;
                    }
                case 'near_bounds':
                    {
                        var poi = ui.item.poi;
                        var latLng = new LatLng(poi.lat, poi.lng);
                        var types = ui.item.types;
                        var bounds = mapManager.getBounds();
                        var poiBounds = new Bounds(poi.n, poi.e, poi.s, poi.w);
                        API.get_mcz({
                            post: {
                                zoom: mapManager.getZoom(),
                                bounds: bounds.serialize(),
                                poiBounds: poiBounds,
                                types: types
                            },
                            success: function(res) {
                                var latLng = LatLng.deserialize(res.latLng);
                                var zoom = res.zoom;
                                mapManager.setPoiId(poi.id);
                                mapManager.setTypes(types);
                                mapManager.panTo(latLng, zoom);
                            }
                        });
                        break;
                    }
                case 'point':
                    {
                        var poi = ui.item.poi;
                        var latLng = new LatLng(poi.lat, poi.lng);
                        mapManager.setPoiId(poi.id);
                        mapManager.panTo(latLng, 16);
                        break;
                    }
                case 'bounds':
                    {
                        var poi = ui.item.poi;
                        var bounds = new Bounds(poi.n, poi.e, poi.s, poi.w);
                        mapManager.setPoiId(poi.id);
                        mapManager.fitBounds(bounds);
                        break;
                    }
            }
        }
//        open: function(event, ui) {
//            $("#list ul").append('<li class="ui-menu-item" role="presentation"><a href="google.com">search fulltext</a></li>');
//        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (item.action === 'types') {
            return $("<li>")
                    .append("<a><div>" + item.value + "</div></a>")
                    .appendTo(ul);
        }
        else {
            return $("<li>")
                    .append("<a><div>" + item.value + "</div><div class='subtitle'>" + item.poi.subName + ", " + item.poi.near + "</div></a>")
                    .appendTo(ul);
        }
    };
});