<html>
    <head>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <link type="text/css" rel="stylesheet" href="/application/js/jquery/ui/custom-theme/jquery-ui.css" />
        <style>
            .ui-autocomplete.ui-menu { padding: 0; font-family: Arial; font-size: 14px; }
            .ui-autocomplete.ui-menu .ui-menu-item a { padding: 5px 7px; line-height: 1.2em; }
            .ui-autocomplete.ui-menu .ui-menu-item a.ui-state-focus { margin: 0; border: none; }
            #searchInput { outline: none; font-size: 14px; padding: 5px 5px 5px 6px; font-family: Arial; }
            .label { font-size: 14px; white-space: nowrap; }
            .label.type { padding: 6px 1px; }
            .place { font-size: 11px; color: #999; display: inline-block; }
            .sub { float: right; font-size: 10px; color: #aaa; padding-top: 2px; }
        </style>
    </head>
    <body>
        <script>
            $(function() {

                $('#searchInput').autocomplete({
                    source: "/test/search",
                    select: function(event, ui) {
                        console.log(ui);
                    },
                    position: {
                        my: "left top-1px"
                    }
                }).data("ui-autocomplete")._renderItem = renderItem;

                function renderItem(ul, item) {

                    var types = item.types;
                    var label = item.label;
                    var poi = item.poi;
                    var place = '';
                    
                    if (poi !== null) {
                        place = (poi.near !== null ? poi.near + ', ' : '') + (poi.country !== null ? poi.country : '');
                        //poi.name.toLowerCase().indexOf(poi.sub) === -1 ? poi.subName.toLowerCase() + ' near ' : ''
                    }

                    if (types !== null) {
                        if (poi !== null) {
                            return $('<li>')
                                    //.append('<a><div class="sub">' + poi.subName + '</div><span class="label">' + label + '</span><span class="place"> ' + place + '</span></a>')
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
    </body>
</html>