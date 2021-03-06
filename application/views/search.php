<html>
    <head>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/controllers/APIBroker.js"></script>
        <link type="text/css" rel="stylesheet" href="/application/js/jquery/ui/custom-theme/jquery-ui.css" />

        <style>
            .ui-autocomplete.ui-menu { padding: 0; font-family: Arial; font-size: 14px; opacity: 1; }
            .ui-autocomplete.ui-menu .ui-menu-item a { padding: 5px 7px; line-height: 1.2em; }
            .ui-autocomplete.ui-menu .ui-menu-item a.ui-state-focus { margin: 0; border: none; }
            .ui-autocomplete.ui-menu .ui-menu-item a.fulltextButton.ui-state-focus { margin: 0; border-top: solid 1px #aaa; }

            #searchInput { outline: none; font-size: 14px; padding: 5px 5px 5px 6px; font-family: Arial; }
            #suggestList { }

            .label { font-size: 14px; white-space: nowrap; }
            .label.type { padding: 6px 1px; }
            .place { font-size: 11px; color: #999; display: inline-block; }
            .sub { float: right; font-size: 10px; color: #aaa; padding-top: 2px; }

            a.fulltextButton { background-color: #f6f6f6; border-top: solid 1px #aaa; }
            a.fulltextButton div { padding: 3px 0; font-size: 11px; color: #666; font-weight: bold; text-align: center; }
        </style>
    </head>
    <body>
        <script>
            $(function() {

                $('#searchInput').autocomplete({
                    source: "/api/suggest",
                    appendTo: '#suggestList',
                    position: {my: "left top-1px"},
                    // Define action on user select
                    select: function(event, ui) {

                        var poiBrief = ui.item.poi;
                        var types = ui.item.types;

                        var poiId = poiBrief === null ? 0 : poiBrief.id;
                        var types = types === null ? [] : types;

                        // Do call Map.loadData
                        console.log(poiId, types);

                        APIBroker.loadData({
                            post: {
                                vBounds: 'Bounds(13 35, 17 37)',
                                zoom: 10,
                                poiId: poiId,
                                types: types,
                                flags: ['panToPoi', 'zoomToTypes', 'poiInfo', 'poiCard']
                            },
                            success: function(res) {
                                console.log(res);
                            }
                        })
                    },
                    // When suggest menu opens
                    open: function(event, ui) {
                        $("#suggestList ul").append('<li class="ui-menu-item" role="presentation"><a href="/test/fulltext?term=' + $(this).val() + '" class="fulltextButton"><div>See more results &raquo;</div></a></li>');
                    }
                }).data("ui-autocomplete")._renderItem = renderItem;

                function renderItem(ul, item) {

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
                                    .append('<a><div class="sub">' + poi.subName + '</div><div class="label">' + label + '</div><div class="place">' + place + '</div></a>')
                                    .appendTo(ul);
                        }
                        else {
                            return $('<li>')
                                    .append('<a><div class="label type">' + label + '</div></a>')
                                    .appendTo(ul);
                        }
                    }
                    else {
                        if (poi !== null) {
                            return $('<li>')
                                    .append('<a><div class="sub">' + poi.subName + '</div><div class="label">' + label + '</div><div class="place">' + place + '</div></a>')
                                    .appendTo(ul);
                        }
                    }
                }
            });
        </script>
        <input id="searchInput" type="text" style="width: 330px;" />
        <ul id="suggestList"></ul>

    </body>
</html>