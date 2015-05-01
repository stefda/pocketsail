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
        <script src="/application/js/brokers/POIBroker.js"></script>
        <script src="/application/js/brokers/API2Broker.js"></script>
        <script src="/application/js/brokers/PhotoBroker.js"></script>

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

        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>

        <script>

            var validator = new Validator();
            var init = false;

            function iframe_init() {
                init = true;
            }

            $(function () {

                $('body').click(function () {
                    $('#photoSettingsMenu').hide();
                });


                var poiId = <?= $poi->id ?>;
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

                map.initGoogleMap();

                // Show latLng marker
                var marker = new google.maps.Marker({
                    map: map.map,
                    position: latLng.toGoogleLatLng(),
                    draggable: true
                });

                // Update latLng when marker is dropped
                google.maps.event.addListener(marker, 'dragend', function (e) {
                    latLng = LatLng.fromGoogleLatLng(e.latLng);
                });

                // Set-up border drawing facilities
                var polyline = null;
                var polygon = null;

                if (border !== null) {

                    var polygon = new google.maps.Polygon({
                        map: map.map,
                        path: border.toGooglePath(),
                        clickable: true,
                        editable: true,
                        strokeColor: 'red'
                    });

                    google.maps.event.addListener(polygon, 'rightclick', function (e) {

                        if (e.vertex !== undefined) {
                            this.getPath().removeAt(e.vertex);

                            // Replace back to polyline when only one vertex
                            if (this.getPath().length === 1) {
                                polyline.setPath(this.getPath());
                                polyline.setMap(map.map);
                                polygon.setMap(null);
                                polygon = null;
                            }
                        }
                    });
                }

                google.maps.event.addListener(map.map, 'click', function (e) {

                    if (polyline === null) {

                        // Create new polyline
                        polyline = new google.maps.Polyline({
                            map: map.map,
                            editable: true,
                            clickable: true,
                            strokeColor: 'red',
                            path: [e.latLng]
                        });

                        // Remove vertices on rightclick
                        google.maps.event.addListener(polyline, 'rightclick', function (e) {
                            if (e.vertex !== undefined) {
                                this.getPath().removeAt(e.vertex);
                            }
                        });

                        // Replace with polygon when click on first vertex
                        google.maps.event.addListener(polyline, 'click', function (e) {

                            if (e.vertex === 0) {

                                // Create replacement polygon
                                polygon = new google.maps.Polygon({
                                    map: map.map,
                                    path: polyline.getPath(),
                                    clickable: true,
                                    editable: true,
                                    strokeColor: 'red'
                                });

                                // Remove polyline from map
                                polyline.setMap(null);

                                // Rightclick removes vertex
                                google.maps.event.addListener(polygon, 'rightclick', function (e) {

                                    if (e.vertex !== undefined) {
                                        this.getPath().removeAt(e.vertex);

                                        // Replace back to polyline when only one vertex
                                        if (this.getPath().length === 1) {
                                            polyline.setPath(this.getPath());
                                            polyline.setMap(map.map);
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
                    $('.tpl-text-large,.tpl-details-small,.tpl-details-large').focus(function () {
                        $(this).addClass('tpl-focus');
                    });

                    // Change border on blur
                    $('.tpl-text-large,.tpl-text-small,.tpl-details-small,.tpl-details-large').blur(function () {
                        $(this).removeClass('tpl-focus');
                    });

                    // Autosize all textareas
                    $('textarea').autosize({
                        append: false
                    });

                    // Contact delete button
                    $('.tpl-delete-button').click(function () {
                        $(this).closest('tr').remove();
                    });

                    // Details button
                    $('.tpl-details-button').click(function (e) {
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

                    $('.tpl-details textarea').keyup(function () {
                        if ($(this).val() === '') {
                            $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').removeClass('tpl-visible');
                            $(this).removeClass('attr-include');
                        } else {
                            $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').addClass('tpl-visible');
                            $(this).addClass('attr-include');
                        }
                    });
                }

                $('#canvasResizeButton').click(function () {

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

                    google.maps.event.trigger(map.map, 'resize');
                    map.setCenter(center);
                });

                // Load subs for changed cat
                $('#catSelectButton').change(function () {

                    POIBroker.getSubs({
                        post: {
                            cat: $(this).val()
                        },
                        success: function (subs) {
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
                $(window).scroll(function () {
                    if ($(window).scrollTop() > 20) {
                        $('#head').css('box-shadow', '0 1px 2px rgba(0, 0, 0, 0.2)');
                    } else {
                        $('#head').css('box-shadow', 'none');
                    }
                });

                // Change template when sub is changed
                $('#subSelectButton').change(function () {

                    var name = $('[name=name]').val();
                    var label = $('[name=label]').val();
                    var attrs = $('.attr:visible,.attr-include').serialize();
                    var border = polygon === null ? null : Polygon.fromGooglePath(polygon.getPath().getArray()).toWKT();

                    POIBroker.getTemplate({
                        post: $.param({
                            cat: $('#catSelectButton').val(),
                            sub: $(this).val(),
                            name: name,
                            label: label,
                            latLng: latLng.toWKT(),
                            border: border
                        }) + '&' + attrs,
                        success: function (html) {
                            var data = $(html);
                            var left = data.find('.tpl-column-left .replace');
                            var right = data.find('.tpl-column-right .replace');
                            $('body').find('.tpl-column-left .replace').html(left.html());
                            $('body').find('.tpl-column-right .replace').html(right.html());
                            initUI();
                        }
                    });
                });

                validator.add(function () {

                    var cat = $('[name=cat]').val().trim();
                    var sub = $('[name=sub]').val().trim();
                    var nearId = $('[name=nearId]').val();
                    var countryId = $('[name=countryId]').val();

                    if (this.valid && (cat === 'geo' || cat === 'admin' || sub === 'marina') && polygon === null) {
                        this.valid = confirm('Do you wish to save this POI without a border?');
                    }

                    if (this.valid && nearId === '' || countryId === '') {
                        this.valid = confirm('Do you wish to save this POI without a near place and/or country?');
                    }
                    return this.valid;
                });

                $('#cancelButton').click(function () {
                    window.location = '/';
                });

                $('#saveButton').click(function () {

                    var cat = $('[name=cat]').val().trim();
                    var sub = $('[name=sub]').val().trim();
                    var nearId = $('[name=nearId]').val();
                    var countryId = $('[name=countryId]').val();
                    var name = $('[name=name]').val().trim();
                    var label = $('[name=label]').val().trim();
                    var url = $('[name=url]').val().trim();
                    var attrs = $('.attr:visible,.attr-include').serialize();
                    var border = polygon === null ? null : Polygon.fromGooglePath(polygon.getPath().getArray()).toWKT();

                    if (validator.validate()) {
                        APIBroker.updatePoi({
                            post: $.param({
                                id: poiId,
                                name: name,
                                label: label,
                                url: url,
                                nearId: nearId,
                                countryId: countryId,
                                cat: cat,
                                sub: sub,
                                latLng: latLng.toWKT(),
                                border: border
                            }) + '&' + attrs,
                            success: function (res) {
                                if (res) {
                                    window.location = '/';
                                }
                            }
                        });
                    }
                });

                $('#photoUploadButton').click(function () {
                    $('#photosInput').click();
                });

                $('#photosInput').change(function () {
                    $('#photoUploadForm').submit();
                });

                $('#photoUploadFrame').load(function () {

                    if (!init) {
                        init = true;
                        return;
                    }

                    var text = $(this).contents().find('body').text();
                    var res = $.parseJSON(text);

                    if (res.status === 'OK') {
                        var ids = res.ids;
                        show_photos(ids);
                        $('#galleryShowButton').val('Hide all photos');
                        if (res.main !== null) {
                            setMainPhoto(res.main);
                        }
                    }
                });


                var galleryVisible = false;

                $('#galleryShowButton').click(function () {

                    if (galleryVisible) {
                        galleryVisible = false;
                        hide_photos();
                        $('#galleryShowButton').val('Show all photos');
                        return;
                    }

                    PhotoBroker.get_infos({
                        post: {
                            'poiId': poiId
                        },
                        success: function (res) {
                            show_photos(res.ids, res.descriptions);
                            galleryVisible = true;
                            $('#galleryShowButton').val('Hide all photos');
                        }
                    });
                });

                function hide_photos() {
                    $('#photoPreview').hide();
                    $('#photoPreview').html('');
                }

                function show_photos(ids, descriptions) {

                    // Clear preview contents
                    $('#photoPreview').html('');

                    for (var i = 0; i < ids.length; i++) {
                        if (i % 4 === 0) {
                            $('#photoPreview').append('<div style="clear: both;">');
                        }
                        $('#photoPreview').append(
                                '<div class="photo" style="margin-left: ' + (i % 4 === 0 ? '0' : '12px') + '">' +
                                '<span class="photoSettings" data-id="' + ids[i] + '"></span>' +
                                '<img src="/data/photos/preview/' + ids[i] + '.jpg" />' +
                                '<input type="hidden" name="id[]" value="' + ids[i] + '" />' +
                                '<textarea class="photoDescription" name="description" data-id="' + ids[i] + '">' + (descriptions !== undefined ? descriptions[i] : '') + '</textarea><br />' +
                                '<div>');
                        if (i % 4 === 3) {
                            $('#photoPreview').append('</div>');
                        }
                    }

                    $('.photoDescription').autosize({
                        'append': false
                    });

                    $('#photoPreview').on('blur', '.photoDescription', function () {
                        var id = $(this).data('id');
                        var description = $(this).val();
                        PhotoBroker.set_description({
                            post: {
                                'id': id,
                                'description': description
                            },
                            success: function (res) {
                                //console.log('Description set.');
                            }
                        });
                    });

                    $('.photoSettings').click(function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var id = $(this).data('id');
                        var photo = $(this);
                        $('#photoSettingsMenu').mapmenu({
                            top: e.pageY,
                            left: e.pageX,
                            select: function (e, ui) {
                                var action = ui.item.value;
                                switch (action) {
                                    case 'main':
                                    {
                                        PhotoBroker.set_main({
                                            post: {
                                                'id': id
                                            },
                                            success: function (res) {
                                                setMainPhoto(id, 'auto');
                                            }
                                        });
                                        break;
                                    }
                                    case 'rotate-right':
                                    {
                                        PhotoBroker.rotate_right({
                                            post: {
                                                'id': id
                                            },
                                            success: function (res) {
                                                photo.closest('.photo').find('img').attr('src', '/data/photos/preview/' + id + '.jpg?' + new Date().getTime());
                                            }
                                        });
                                        break;
                                    }
                                    case 'rotate-left':
                                    {
                                        PhotoBroker.rotate_left({
                                            post: {
                                                'id': id
                                            },
                                            success: function (res) {
                                                photo.closest('.photo').find('img').attr('src', '/data/photos/preview/' + id + '.jpg?' + new Date().getTime());
                                            }
                                        });
                                        break;
                                    }
                                }
                                $('#photoSettingsMenu').hide();
                            }
                        });
                    });

                    $('#photoPreview').show();
                }

                function setMainPhoto(id, offset) {

                    var img = $('<img src="/data/photos/gallery/' + id + '.jpg" />');
                    img.load(function () {
                        initMainPhotoPosition(offset);
                    });
                    $('#mainPhotoWrapper').html('');
                    $('#mainPhotoWrapper').append(img);
                    $('#mainPhotoContainment').data('id', id);
                }

                function initMainPhotoPosition(offset) {

                    var photoHeight = $('#mainPhotoWrapper').innerHeight();
                    var galleryHeight = $('#gallery').innerHeight();
                    var photoContainmentHeight = photoHeight + photoHeight - galleryHeight;
                    var photoContainmentOffset = -Math.ceil(photoHeight - galleryHeight);

                    $('#mainPhotoContainment').innerHeight(photoContainmentHeight);
                    $('#mainPhotoContainment').css('top', photoContainmentOffset);

                    if (offset === undefined) {
                        $('#mainPhotoWrapper').css('top', (-photoContainmentOffset / 2) + 'px');
                    } else if (offset !== 'given') {
                        $('#mainPhotoWrapper').css('top', offset + 'px');
                    }

//                    if (offset !== undefined && offset !== 'auto') {
//                        console.log('B' + offset + 'px');
//                        $('#mainPhotoWrapper').css('top', offset + 'px');
//                    }

                    $('#mainPhotoWrapper').draggable({
                        axis: 'y',
                        containment: $('#mainPhotoContainment'),
                        stop: function (e, ui) {
                            var offset = $('#mainPhotoWrapper').position().top;
                            PhotoBroker.set_offset({
                                post: {
                                    'id': $('#mainPhotoContainment').data('id'),
                                    'offset': offset
                                },
                                success: function (res) {
//                                    console.log(res);
                                }
                            });
                        }
                    });
                }

                initMainPhotoPosition('given');
                initUI();
            });

        </script>

        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />

        <style>
            .photo { position: relative; float: left; }
            .photo img { display: block; }
            .photo .photoSettings { display: block; position: absolute; top: 180px; left: 180px; width: 29px; height: 29px; background-image: url('/application/images/settings-button.png'); }
            .photo .photoSettings:hover { cursor: pointer; cursor: hand; }
            .photoDescription { resize: none; width: 216px; margin-top: 7px; }

            #photoUploadFrame { display: none; }

            #mainPhotoWrapper { position: absolute; cursor: pointer; cursor: n-resize; }
            #mainPhotoWrapper img { display: block; }
            #mainPhotoContainment { position: absolute; }



        </style>

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

                    <div id="gallery" class="tpl-gallery" style="position: relative; overflow: hidden;">

                        <div id="mainPhotoContainment" data-id="<?= $mainPhotoInfo !== NULL ? $mainPhotoInfo['id'] : '' ?>">
                            <div id="mainPhotoWrapper" style="top: <?= $mainPhotoInfo !== NULL && $mainPhotoInfo['offset'] !== NULL ? $mainPhotoInfo['offset'] . 'px' : 'auto' ?>;">
                                <? if ($mainPhotoInfo !== NULL): ?>
                                    <img src="/data/photos/gallery/<?= $mainPhotoId ?>.jpg" />
                                <? endif; ?>
                            </div>
                        </div>

                        <form id="photoUploadForm" name="photoUploadForm" method="post" enctype="multipart/form-data" action="/photo/upload" target="photoUploadFrame">
                            <input type="hidden" name="poiId" value="<?= $poi->id ?>" /><br />
                            <input type="button" value="Upload Photos" id="photoUploadButton" style="position: absolute; bottom: 10px; right: 10px; width: 150px;" />
                            <input type="button" value="Show all photos" id="galleryShowButton" style="position: absolute; bottom: 40px; right: 10px;  width: 150px;" />
                            <div style="height: 0px; overflow: hidden;">
                                <input id="photosInput" type="file" name="photo[]" multiple /><br />
                            </div>
                        </form>
                    </div>

                </div>

                <div style="width: 100%;" id="photoPreview" style="display: none;"></div>
                <div style="clear: both;"></div>

                <iframe name="photoUploadFrame" id="photoUploadFrame" src="/photo/index" onload="iframe_init()"></iframe>

            </div>

            <div>
                <?= include_edit_template($poi->cat, $poi->sub) ?>
            </div>

        </div>

        <div style="clear: both; width: 100%; height: 100px; background-color: #e9eaeb; margin-top: 40px; padding-top: 20px;">
        </div>

        <ul id="photoSettingsMenu" style="position: absolute; display: none;">
            <li data-value="main">Set main</li>
            <li data-value="rotate-left">Rotate left</li>
            <li data-value="rotate-right">Rotate right</li>
        </ul>

    </body>
</html>