<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="icon" type="image/png"  href="/application/images/favicon4.png">

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/broker.js"></script>
        <script src="/application/js/utils.js"></script>
        <script src="/application/js/brokers/MapBroker.js"></script>
        <script src="/application/js/brokers/APIBroker.js"></script>

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

            var poiId = <?= $poi->id ?>;
            var cat = '<?= $poi->cat ?>';
            var sub = '<?= $poi->sub ?>';
            var latLng = LatLng.fromGeoJson(<?= $poi->latLng->js() ?>);

            $(function () {

                /**
                 * Define UI elements
                 */

                var canvas = $('#canvas');

                /**
                 * Initialize map
                 */

                map = new Map(canvas, {
                    cache: true
                });

                map.setParam('poiId', poiId);

                map.loadData('search', function (data) {
                    this.handleData(data);
                    this.initCanvas(function () {
                        this.redraw();
                    });
                });

                $('.nearGroup').click(function (e) {
                    var idsStr = $(this).attr('data-poiids');
                    var poiIds = $.parseJSON(idsStr);
                    map.setParam('poiId', poiId);
                    map.setParam('poiIds', poiIds);
                    map.loadData('quick', function (data) {
                        this.handleData(data);
                        this.redraw();
                    });
                    e.preventDefault();
                });

                $('.near').click(function (e) {
                    var id = $(this).attr('poiId');
                    map.setParam('poiId', poiId);
                    map.setParam('poiIds', [id]);
                    map.loadData('quick', function (data) {
                        this.handleData(data);
                        this.redraw();
                    });
                    e.preventDefault();
                });

                $('.seeAll').click(function (e) {
                    var cat = $(this).data('cat');
                    var group = $('.moreGroup[data-cat=\'' + cat + '\']');
                    if (group.is(':visible')) {
                        group.slideUp(100);
                        $(this).html('show all');
                    } else {
                        group.slideDown(100);
                        $(this).html('show less');
                    }
                    e.preventDefault();
                });

                $('.findNearest').click(function (e) {
                    var sub = $(this).data('sub');
                    map.setParam('types', [sub]);
                    map.loadData('search', function (data) {
                        this.handleData(data);
                        this.redraw();
                    });
                    e.preventDefault();
                });
            });

        </script>

        <style>

            #canvas { width: 100%; height: 100%; }

            .right-column { float: right; width: 300px; margin-top: 75px; }
            .left-column { float: left; width: 580px; margin-top: 75px; }

            .findNearest { text-decoration: none; color: #3079ed; font-weight: bold; font-size: 13px; }
            .nearGroup { text-decoration: none; color: #3079ed; font-weight: bold; font-size: 13px; }
            .near { text-decoration: none; color: #4c8efc; font-size: 12px; }
            .seeAll { text-decoration: none; color: #4c8efc; font-size: 12px; }
            .moreGroup { display: none; }

        </style>

        <link type="text/css" rel="stylesheet" href="/application/layout/global.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" id="mapStyle" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div id="header">
            <div style="margin: 12px 0 0 20px;">
                <img src="/application/images/logo.png"/>
            </div>
        </div>

        <div id="content">

            <div class="right-column">

                <div id="canvasWrapper" style="height: 200px; float: none;">
                    <div id="canvas"></div>
                </div>

                <div style="padding: 20px 10px 0 0;">

                    <div style="margin: 0 0 20px 0;">
                        <div style="color: #444; font-size: 14px; font-weight: bold; margin-bottom: 8px;">Find nearest</div>
                        <div style="margin-bottom: 1px;">
                            <a class="findNearest" href="" data-sub="marina">
                                Marina
                            </a>
                        </div>
                        <div style="margin-bottom: 1px;">
                            <a class="findNearest" href="" data-sub="anchorage">
                                Anchorage
                            </a>
                        </div>
                        <div style="margin-bottom: 1px;">
                            <a class="findNearest" href="" data-sub="buoys">
                                Buoys
                            </a>
                        </div>
                    </div>

                    <div style="color: #444; font-size: 14px; font-weight: bold;">Places within 1 mile</div>

                    <? $cats = ['berthing', 'anchoring', 'attraction', 'goingout', 'shopping', 'refuelling'] ?>

                    <div style="padding-left: 0px;">

                        <? foreach ($cats AS $cat): ?>
                            <? if (isset($nearbys[$cat])): ?>
                                <? $group = $nearbys[$cat] ?>
                                <div style="margin: 10px 0 7px;">
                                    <div>
                                        <a class="nearGroup" href="" data-poiids="<?= htmlspecialchars(json_encode($catIds[$cat])); ?>">
                                            <?= $catsMap[$cat] ?>
                                            (<?= count($group) ?>)
                                        </a>
                                        <? if (count($group) > 1): ?>
                                            <a class="seeAll" href="" data-cat="<?= $cat ?>">show all</a>
                                        <? endif; ?>
                                    </div>
                                    <div style="margin-top: 3px;">
                                        <? for ($i = 0; $i < count($group); $i++): ?>
                                            <? if (count($group) > 1 && $i == 1): ?>
                                                <div class="moreGroup" data-cat="<?= $cat ?>">
                                                <? endif; ?>
                                                <? $nearby = $group[$i] ?>
                                                <div style="margin-bottom: 1px;">
                                                    <a class="near" poiId="<?= $nearby['id'] ?>" href="">
                                                        <? if ($nearby['name'] == ''): ?>
                                                            (<?= $subsMap[$nearby['sub']] ?>)
                                                        <? else: ?>
                                                            <?= $nearby['name'] ?>
                                                        <? endif; ?>
                                                    </a>
                                                    <span style="font-size: 11px; color: #333; margin-left: 3px;"><?= round($nearby['distance'], 1) ?> km</span>
                                                </div>
                                            <? endfor; ?>
                                            <? if (count($group) > 1): ?>
                                            </div>
                                        <? endif; ?>
                                    </div>
                                </div>
                            <? endif; ?>
                        <? endforeach; ?>

                    </div>

                </div>

            </div>

            <div class="left-column">

                <div style="width: 580px; height: 200px; background-color: red; overflow: hidden; position: relative;">
                    <? if ($mainPhotoInfo !== NULL): ?>
                        <img src="/data/photos/gallery/<?= $mainPhotoInfo['id'] ?>.jpg" style="position: absolute; bottom: -<?= $mainPhotoInfo['offset'] ?>px;"/>
                    <? endif; ?>
                </div>

                <div class="tpl-section" style="border-radius: 0 0 2px 2px;">
                    <div class="tpl-section-wrapper">

                        <div style="font-size: 18px; font-weight: bold; color: #444; margin: 4px 0 6px 0;">
                            <?= $poi->name ?> (<?= $subsMap[$poi->sub] ?>)
                        </div>
                        <div style="font-size: 14px;">
                            <?= $poi->latLng->latFormatted() . " - " . $poi->latLng->lngFormatted() ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </body>
</html>