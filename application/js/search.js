
$(function () {

    /**
     * Define UI elements
     */

    var searchInput = $('#searchInput');

    /**
     * Initi autocomplete
     */

    searchInput.autocomplete({
        source: "/api/suggest",
        appendTo: '#suggestList',
        position: {my: "left top-1px"},
        response: function (e, ui) {
            if (ui.content.length === 1) {
                ui.content.unshift({
                    nores: true
                });
            }
        },
        // Define action on user select
        select: function (e, ui) {

            map.clearAllParams();

            if (ui.item.fulltext) {
                window.location = "/test/fulltext?term=" + ui.item.value;
                return;
            }

            if (ui.item.poi !== null) {
                map.setParam('poiId', ui.item.poi.id);
            } else {
                map.clearParam('poiId');
            }

            if (ui.item.types !== null) {
                map.setParam('types', ui.item.types);
            } else {
                map.clearParam('types');
            }

            map.loadData('search', function (data) {
                console.log(data);
                map.handleData(data);
                map.redraw();
            });
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {

        if (item.nores) {
            return $('<li>')
                    .append('<div style="text-align: center; padding: 11px 0; color: #d0d1d2;">No suggestions found</div>')
                    .appendTo(ul);
        }

        if (item.fulltext) {
            return $('<li>')
                    .append('<a href="/test/fulltext?term=' + item.label + '" class="fulltextButton"><div>See fulltext search results for <i>' + item.label + '</i> &raquo;</div></a>')
                    .appendTo(ul);
        }

        var types = item.types;
        var label = item.label;
        var poi = item.poi;
        var place = '';

        if (poi !== null) {
            place = (poi.nearName !== null ? poi.nearName + ', ' : '') + (poi.countryName !== null ? poi.countryName : '');
        }

        if (types !== null) {
            if (poi !== null) {
                return $('<li>')
                        .append('<a><div class="sub">' + poi.subName + '</div><div class="name">' + label + '</div><div class="place">' + place + '</div></a>')
                        .appendTo(ul);
            }
            else {
                return $('<li>')
                        .append('<a><div class="name type">' + label + '</div></a>')
                        .appendTo(ul);
            }
        }
        else {
            if (poi !== null) {
                return $('<li>')
                        .append('<a><div class="sub">' + poi.subName + '</div><div class="name">' + label + '</div><div class="place">' + place + '</div></a>')
                        .appendTo(ul);
            }
        }
    }
});