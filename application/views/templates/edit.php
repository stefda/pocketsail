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
            var poiId = <?= $poi->id ?>;
            var cat = '<?= $poi->cat ?>';
            var sub = '<?= $poi->sub ?>';
            var latLng = LatLng.fromWKT('<?= $poi->latLng->toWKT() ?>');
            var border = Polygon.fromWKT('<?= $poi->border === null ? 'NULL' : $poi->border->toWKT() ?>');

            $(function() {

                var map = new Map({
                    canvas: 'canvas',
                    center: latLng,
                    zoom: 16,
                    flags: ['excludePoiLabel'],
                    poiId: poiId
                });

                $('#zoom').click(function() {
                    map.setPoiIds([121]);
                    map.loadData(['zoomToPois']);
                });

                //console.log(map.googleMap);
                var marker = new google.maps.Marker({
                    map: map.googleMap,
                    position: latLng.toGoogleLatLng(),
                    draggable: true,
                    crossOnDrag: false,
                    icon: {
                        anchor: new google.maps.Point(5, 23),
                        url: '/application/layout/images/add-icon.png'
                    }
                });

                google.maps.event.addListener(map.googleMap, 'click', function(e) {
                    marker.setPosition(e.latLng);
                });

                $('.tpl-select').select();
                $('.tpl-select-button').selectButton();

                // Inputs outline
                $('a,input,textarea').focus(function() {
                    $(this).addClass('ps-ui-focus');
                });

                $('a,input,textarea').blur(function() {
                    $(this).removeClass('ps-ui-focus');
                });

                $('textarea').autosize({
                    append: false
                });

                $('.tpl-delete-button').click(function() {
                    $(this).closest('tr').remove();
                });

                // Hacky, but works
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

                $('#canvasResizeButton').click(function() {
                    var center = map.getCenter();
                    if ($('.tpl-canvas-wrapper').hasClass('tpl-canvas-wrapper-large')) {
                        $('.tpl-canvas-wrapper').removeClass('tpl-canvas-wrapper-large');
                    } else {
                        $('.tpl-canvas-wrapper').addClass('tpl-canvas-wrapper-large');
                    }
                    google.maps.event.trigger(map.googleMap, 'resize');
                    map.setCenter(center);
                });

                $('#saveButton').click(function() {
                    if (validator.validate()) {
                        var name = $('[name=name]').val();
                        var attrs = $('.attr:visible,.attr-include').serialize();
                        TestBroker.save_data({
                            post: $.param({
                                id: poiId,
                                name: name,
                                cat: cat,
                                sub: sub,
                                latLng: latLng.toWKT(),
                                border: border === null ? null : border.toWKT()
                            }) + '&' + attrs,
                            success: function(res) {
                                //console.log(res);
                                location.reload();
                            }
                        });
                    } else {
                        alert('Error');
                    }
                });
            });

        </script>

        <style>

            html, body { font-family: Arial; background-color: #fff; }
            body { overflow-y: scroll; }
            a, input, textarea, select { outline: none; font-family: Arial; display: inline-block; margin: 0; }
            h1 { font-size: 14px; margin: 0 0 7px 0; font-weight: bold; color: #555; }
            h2 { font-size: 12px; margin: 0 2px 7px 0; font-weight: normal; color: #333; display: inline; }
            input { font-size: 13px; border: solid 1px #d0d1d2; padding: 5px 7px; }
            textarea { display: block; box-sizing: border-box; font-size: 13px; border: solid 1px #d0d1d2; padding: 5px 7px; line-height: 1.4em; }

            .tpl-section { width: 600px; margin-bottom: 20px; background-color: #f7f8f9; border-radius: 3px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); }
            .tpl-section-wrapper { padding: 10px 12px; }
            .tpl-subsection { margin-top: 10px; padding-top: 10px; border-top: solid 1px #e0e1e2; padding-bottom: 5px; }

            .tpl-details-large { width: 100%; height: 48px; resize: none; }
            .tpl-details-small { width: 100%; height: 24px; resize: none; }

            .tpl-table { border-collapse: collapse; }
            .tpl-table td { padding-bottom: 3px; }
            .tpl-table-item-label { text-align: right; width: 70px; font-size: 12px; }
            .tpl-table-item-value { padding-left: 5px; }

            .tpl-text-small { font-size: 12px; padding: 2px 3px; }
            .tpl-note { font-size: 11px; color: #a0a1a2; }

            .tpl-details-button { display: none; cursor: pointer; margin-left: 10px; padding-right: 20px; color: #a0a1a2; font-size: 12px; text-decoration: none; background-image: url('/application/layout/images/details-arrows.png'); background-repeat: no-repeat; background-position: 40px -10px; }
            .tpl-details-button.tpl-stay-visible { display: inline; background-repeat: no-repeat; background-position-y: 6px; }
            .tpl-details-button.tpl-visible { display: inline; color: #3079ed; background-position-y: -40px; }
            .tpl-details-button.tpl-stay-visible.tpl-visible { background-position-y: -24px; }
            .tpl-has-details-button:hover .tpl-details-button { display: inline; }
            .tpl-details { display: none; margin-top: 8px; }
            .tpl-details td { padding: 4px 0 8px; }

            .tpl-delete-button { cursor: pointer; display: block; width: 10px; height: 10px; background-repeat: no-repeat; background-image: url('/application/layout/images/delete-cross.png'); }
            .tpl-delete-button:hover { background-position-y: -10px; }

            .tpl-canvas-wrapper { height: 200px; margin-bottom: 20px; border: solid 1px #d0d1d2; }
            .tpl-canvas-wrapper-large { height: 500px; }
            .tpl-canvas-resize-button { cursor: pointer; position: relative; top: -5px; left: 425px; background-color: #f7f8f9; border-radius: 3px; box-shadow: 0 0 3px rgba(0, 0, 0, 0.4); width: 49px; height: 10px; background-image: url('/application/layout/images/arrow-down.png'); background-repeat: no-repeat; background-position: 21px 3px; }
            .tpl-canvas-wrapper-large .tpl-canvas-resize-button { background-image: url('/application/layout/images/arrow-up.png'); }



        </style>

        <link type="text/css" rel="stylesheet" id="mapStyle" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div style="position: fixed; top: 20px; left: 960px;">
            <input type="button" id="saveButton" value="Save" />
        </div>

        <div style="width: 900px; margin: 20px auto;">
            
            <div class="tpl-canvas-wrapper" id="canvasWrapper">
                <div id="canvas" style="width: 100%; height: 100%;"></div>
                <div class="tpl-canvas-resize-button" id="canvasResizeButton"></div>
            </div>

            <? include_edit_template($poi->cat, $poi->sub); ?>

        </div>

    </body>
</html>