<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/jquery/utils.js"></script>

        <script src="/application/js/brokers/APIBroker.js"></script>
        <script src="/application/js/brokers/PoiBroker.js"></script>

        <script src="/application/js/geo/Geo.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LineString.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Bounds.js"></script>
        <script src="/application/js/geo/ViewBounds.js"></script>
        <script src="/application/js/Map.js"></script>
        <script src="/application/js/MapStyle.js"></script>

        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>
        <script src="/application/js/geo/Projector.js"></script>
        <script src="/application/js/geo/Position.js"></script>

        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/brokers/TestBroker.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>

        <script>

            var validator = new Validator();

            $(function() {

                var id = <?= $poi->id ?>;
                var cat = '<?= $poi->cat ?>';
                var sub = '<?= $poi->sub ?>';
                var latLng = LatLng.fromWKT('<?= $poi->latLng->toWKT() ?>');
                var border = Polygon.fromWKT('<?= $poi->border !== null ? $poi->border->toWKT() : 'NULL' ?>');

                // Initialise map
                var map = new Map({
                    canvas: 'canvas',
                    center: latLng,
                    zoom: 16,
                    border: border
                });

                // Show latLng marker
                var marker = new google.maps.Marker({
                    map: map.googleMap,
                    position: latLng.toGoogleLatLng(),
                    draggable: true
                });

                // Update latLng when marker is dropped
                google.maps.event.addListener(marker, 'dragend', function(e) {
                    latLng = LatLng.fromGoogleLatLng(e.latLng);
                });

                // Set-up border drawing facilities
                var polyline = null;
                var polygon = null;

                if (border !== null) {

                    var polygon = new google.maps.Polygon({
                        map: map.googleMap,
                        path: border.toGooglePath(),
                        clickable: true,
                        editable: true,
                        strokeColor: 'red'
                    });

                    google.maps.event.addListener(polygon, 'rightclick', function(e) {

                        if (e.vertex !== undefined) {
                            this.getPath().removeAt(e.vertex);

                            // Replace back to polyline when only one vertex
                            if (this.getPath().length === 1) {
                                polyline.setPath(this.getPath());
                                polyline.setMap(map.googleMap);
                                polygon.setMap(null);
                                polygon = null;
                            }
                        }
                    });
                }

                google.maps.event.addListener(map.googleMap, 'click', function(e) {

                    if (polyline === null) {

                        // Create new polyline
                        polyline = new google.maps.Polyline({
                            map: map.googleMap,
                            editable: true,
                            clickable: true,
                            strokeColor: 'red',
                            path: [e.latLng]
                        });

                        // Remove vertices on rightclick
                        google.maps.event.addListener(polyline, 'rightclick', function(e) {
                            if (e.vertex !== undefined) {
                                this.getPath().removeAt(e.vertex);
                            }
                        });

                        // Replace with polygon when click on first vertex
                        google.maps.event.addListener(polyline, 'click', function(e) {

                            if (e.vertex === 0) {

                                // Create replacement polygon
                                polygon = new google.maps.Polygon({
                                    map: map.googleMap,
                                    path: polyline.getPath(),
                                    clickable: true,
                                    editable: true,
                                    strokeColor: 'red'
                                });

                                // Remove polyline from map
                                polyline.setMap(null);

                                // Rightclick removes vertex
                                google.maps.event.addListener(polygon, 'rightclick', function(e) {

                                    if (e.vertex !== undefined) {
                                        this.getPath().removeAt(e.vertex);

                                        // Replace back to polyline when only one vertex
                                        if (this.getPath().length === 1) {
                                            polyline.setPath(this.getPath());
                                            polyline.setMap(map.googleMap);
                                            polygon.setMap(null);
                                            polygon = null;
                                        }
                                    }
                                });
                            }
                        });
                    } else if (polygon === null) {
                        var path = polyline.getPath();
                        path.push(e.latLng);
                    } else {
                        var path = polygon.getPath();
                        path.push(e.latLng);
                    }
                });

                function initUI() {

                    // Style select inputs
                    $('.tpl-select').select();

                    // Style select-button inputs
                    $('.tpl-select-button').selectButton();

                    // Change border on focus
                    $('.tpl-text-large,.tpl-details-small,.tpl-details-large').focus(function() {
                        $(this).addClass('tpl-focus');
                    });

                    // Change border on blur
                    $('.tpl-text-large,.tpl-text-small,.tpl-details-small,.tpl-details-large').blur(function() {
                        $(this).removeClass('tpl-focus');
                    });

                    // Autosize all textareas
                    $('textarea').autosize({
                        append: false
                    });

                    // Contact delete button
                    $('.tpl-delete-button').click(function() {
                        $(this).closest('tr').remove();
                    });

                    // Details button
                    $('.tpl-details-button').click(function(e) {
                        var elem = $(this).closest('.tpl-has-details-button');
                        if ($(this).hasClass('tpl-stay-visible')) {
                            $(this).removeClass('tpl-stay-visible');
                            elem.next('.tpl-details').hide();
                        } else {
                            $(this).addClass('tpl-stay-visible');
                            var details = elem.next('.tpl-details').show();
                            details.find('textarea').autosize().show().trigger('autosize.resize');
                        }
                        e.preventDefault();
                    });

                    $('.tpl-details textarea').keyup(function() {
                        if ($(this).val() === '') {
                            $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').removeClass('tpl-visible');
                            $(this).removeClass('attr-include');
                        } else {
                            $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').addClass('tpl-visible');
                            $(this).addClass('attr-include');
                        }
                    });
                }

                $('#canvasResizeButton').click(function() {

                    var center = map.getCenter();

                    if ($('#header').hasClass('tpl-header-expanded')) {
                        $('#header').removeClass('tpl-header-expanded');
                        $('#canvasWrapper').css('width', '300px');
                        $('#gallery').show();
                    } else {
                        $('#header').addClass('tpl-header-expanded');
                        $('#gallery').hide();
                        $('#canvasWrapper').css('width', '100%');
                    }

                    google.maps.event.trigger(map.googleMap, 'resize');
                    map.setCenter(center);
                });

                // Load subs for changed cat
                $('#catSelectButton').change(function() {

                    PoiBroker.getSubs({
                        post: {
                            cat: $(this).val()
                        },
                        success: function(subs) {
                            var select = $('#subSelectButton');
                            select.empty();
                            for (var i = 0; i < subs.length; i++) {
                                var sub = subs[i];
                                select.append('<option value="' + sub.id + '">' + sub.name + '</option>');
                            }
                            initUI();
                            select.trigger('change');
                        }
                    });
                });

                // TO IMPROVE!!!
                $(window).scroll(function() {
                    if ($(window).scrollTop() > 20) {
                        $('#head').css('box-shadow', '0 1px 2px rgba(0, 0, 0, 0.2)');
                    } else {
                        $('#head').css('box-shadow', 'none');
                    }
                });

                // Change template when sub is changed
                $('#subSelectButton').change(function() {

                    var name = $('[name=name]').val();
                    var label = $('[name=label]').val();
                    var attrs = $('.attr:visible,.attr-include').serialize();

                    PoiBroker.getTemplate({
                        post: $.param({
                            cat: $('#catSelectButton').val(),
                            sub: $(this).val(),
                            name: name,
                            label: label,
                            latLng: latLng.toWKT(),
                            border: border
                        }) + '&' + attrs,
                        success: function(html) {
                            var data = $(html);
                            var left = data.find('.tpl-column-left .replace');
                            var right = data.find('.tpl-column-right .replace');
                            $('body').find('.tpl-column-left .replace').html(left.html());
                            $('body').find('.tpl-column-right .replace').html(right.html());
                            initUI();
                        }
                    });
                });

                validator.add(function() {

                    var cat = $('[name=cat]').val().trim();
                    var sub = $('[name=sub]').val().trim();

                    if (this.valid && (cat === 'geo' || cat === 'admin' || sub === 'marina') && polygon === null) {
                        this.valid = confirm('Do you wish to save this POI without a border?');
                    }
                    return this.valid;
                });

                $('#cancelButton').click(function() {
                    window.location = '/';
                });

                $('#saveButton').click(function() {

                    var cat = $('[name=cat]').val().trim();
                    var sub = $('[name=sub]').val().trim();
                    var name = $('[name=name]').val().trim();
                    var label = $('[name=label]').val().trim();
                    var url = $('[name=url]').val().trim();
                    var attrs = $('.attr:visible,.attr-include').serialize();
                    var border = polygon === null ? null : Polygon.fromGooglePath(polygon.getPath().getArray()).toWKT();

                    if (validator.validate()) {
                        APIBroker.updatePoi({
                            post: $.param({
                                id: id,
                                name: name,
                                label: label,
                                nearId: 1,
                                countryId: 1,
                                cat: cat,
                                sub: sub,
                                latLng: latLng.toWKT(),
                                border: border
                            }) + '&' + attrs,
                            success: function(res) {
                                if (res) {
                                    window.location = '/';
                                }
                            }
                        });
                    }
                });

                initUI();
            });

        </script>

        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />

    </head>

    <body style="background-color: #f6f7f8; margin: 0; padding: 0;">

        <div id="head" style="z-index: 9999; width: 100%; height: 60px; background-color: #e9eaeb; position: fixed;">
            <div style="float: right; margin: 15px 20px 0 0;">
                <input id="saveButton" class="tpl-button tpl-button-blue" type="button" value="Save POI" />
                <input id="cancelButton" class="tpl-button" type="button" value="Cancel" style="margin-left: 10px;" />
            </div>
            <img src="/application/images/logo.png" style="margin: 14px 0 0 16px;" />
        </div>

        <div style="width: 900px; margin: 0 auto;">

            <div style="padding-top: 80px;">

                <div id="header" class="tpl-header">
                    <div id="canvasWrapper">
                        <div id="canvasResizeButton"></div>
                        <div id="canvas"></div>
                    </div>
                    <div id="gallery" class="tpl-gallery"></div>
                </div>

            </div>

            <div>
                <?= include_edit_template($poi->cat, $poi->sub) ?>
            </div>

        </div>

        <div style="clear: both; width: 100%; height: 100px; background-color: #e9eaeb; margin-top: 40px; padding-top: 20px;">

        </div>

    </body>
</html>