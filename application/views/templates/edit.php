<!DOCTYPE html>
<html>
    <head>

        <title>Pocketsail - edit</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="icon" type="image/png"  href="/application/images/favicon4.png">

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/broker.js"></script>
        <script src="/application/js/utils.js"></script>
        <script src="/application/js/edit.js"></script>
        <script src="/application/js/photo.js"></script>
        <script src="/application/js/ui.js"></script>

        <script src="/application/js/brokers/APIBroker.js"></script>
        <script src="/application/js/brokers/MapBroker.js"></script>
        <script src="/application/js/brokers/POIBroker.js"></script>
        <script src="/application/js/brokers/PhotoBroker.js"></script>

        <script src="/application/js/geo/GeoJSON.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/LatLngBounds.js"></script>
        <script src="/application/js/geo/Proj.js"></script>
        <script src="/application/js/Map.js"></script>
        <script src="/application/js/MapStyle.js"></script>

        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>

        <script>

            var map;
            var validator = new Validator();
            var poiId = <?= $poi->id ?>;
            var mainPhotoId = <?= $mainPhotoInfo === NULL ? 'undefined' : $mainPhotoInfo['id'] ?>;
            var cat = '<?= $poi->cat ?>';
            var sub = '<?= $poi->sub ?>';
            var latLng = LatLng.fromGeoJson(<?= $poi->latLng->js() ?>);
            var border = Polygon.fromGeoJson(<?= $poi->border === NULL ? 'null' : $poi->border->js() ?>);

            $(function () {

                /**
                 * Define UI elements
                 */

                var canvas = $('#canvas');

                /**
                 * Initialize map
                 */

                map = new Map(canvas, {
                    mode: 'edit',
                    borderEdit: true,
                    cache: true
                });

                map.setParam('poiId', poiId);

                map.loadData('edit', function (data) {
                    this.handleData(data);
                    this.initCanvas(function () {
                        this.setDraggableMarkerLatLng(latLng);
                        if (border) {
                            this.setBorderPolygon(border);
                        }
                        this.redraw();
                    });
                });

                initUI();
            });

        </script>

        <link type="text/css" rel="stylesheet" href="/application/layout/global.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

        <style>
            #canvas { width: 100%; height: 100%; }
        </style>

    </head>

    <body style="background-color: #f6f7f8; margin: 0; padding: 0;">

        <div id="header">

            <div style="float: right; margin: 15px 20px 0 0;">
                <input id="saveButton" class="tpl-button tpl-button-blue" type="button" value="Save POI" />
                <input id="cancelButton" class="tpl-button" type="button" value="Cancel" style="margin-left: 10px;" />
            </div>

            <div style="margin: 12px 0 0 20px;">
                <img src="/application/images/logo.png"/>
            </div>

        </div>

        <div id="content">

            <div id="boxheadWrapper">
                <div id="boxhead">

                    <div id="canvasWrapper">
                        <div id="canvasResizeButton"></div>
                        <div id="canvas"></div>
                    </div>

                    <!--
                    -- GALLERY
                    -->
                    <div id="gallery" class="tpl-gallery">

                        <div id="mainPhotoBounds">
                            <div id="mainPhotoWrapper" style="top: <?= $mainPhotoInfo !== NULL && $mainPhotoInfo['offset'] !== NULL ? $mainPhotoInfo['offset'] . 'px' : 'auto' ?>;">
                                <? if ($mainPhotoInfo !== NULL): ?>
                                    <img id="galleryPhoto" src="/data/photos/gallery/<?= $mainPhotoId ?>.jpg" />
                                <? endif; ?>
                            </div>
                        </div>

                        <form id="photosUploadForm" name="photosUploadForm" method="post" enctype="multipart/form-data" action="/photo/upload?ajax" target="photosUploadFrame">

                            <input type="hidden" name="poiId" value="<?= $poi->id ?>" /><br />
                            <input type="button" class="tpl-button tpl-button-blue" value="Show all photos" id="galleryShowButton" style="position: absolute; bottom: 45px; right: 10px;  width: 150px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);" />
                            <input type="button" class="tpl-button tpl-button-blue" value="Upload Photos" id="photosUploadButton" style="position: absolute; bottom: 10px; right: 10px; width: 150px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);" />

                            <div style="height: 0px; overflow: hidden;">
                                <input id="photosInput" type="file" name="photo[]" multiple /><br />
                            </div>

                        </form>

                    </div>
                    <!-- /GALLERY -->

                </div>
            </div>

            <div style="width: 100%;" id="photoPreview" style="display: none;"></div>

            <div style="clear: both;"></div>

            <div>
                <?= include_edit_template($poi->cat, $poi->sub) ?>
            </div>

        </div>

        <div id="footer">
            <div style="width: 230px; margin: 16px auto 0; font-size: 12px; color: #919293;">
                Pocketsail &copy; 2015, with <img src="/application/images/love.png" style="vertical-align: bottom;"/> from London.
            </div>
        </div>

        <ul id="photoSettingsMenu" style="position: absolute; display: none;">
            <li data-value="main">Set main</li>
            <li data-value="rotate-left">Rotate left</li>
            <li data-value="rotate-right">Rotate right</li>
            <li data-value="delete">Delete</li>
        </ul>

        <!--
        -- Photos upload frame
        -->
        <iframe name="photosUploadFrame" id="photosUploadFrame" src="/photo/init_load_frame" onload="photosUploadFrameLoad()"></iframe>

    </body>
</html>