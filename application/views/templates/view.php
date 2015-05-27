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
                    cache: true,
                    disableDefaultUI: true
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

                $('.l').click(function (e) {
                    var href = $(this).attr('href');
                    map.setParam('poiId', poiId);
                    map.setParam('poiIds', [href]);
                    map.setParam('poiUrls', [href]);
                    map.loadData('quick', function (data) {
                        this.handleData(data);
                        this.redraw();
                    });
                    e.preventDefault();
                    e.stopPropagation();
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

            .facility { display: block; float: left; width: 25px; height: 25px; background-image: url('/application/images/facilities.png'); }
            .facility.water { background-position-x: 0px; }
            .facility.electricity { background-position-x: -25px; }
            .facility.showers { background-position-x: -50px; }
            .facility.toilets { background-position-x: -75px; }
            .facility.waste { background-position-x: -100px; }
            .facility.customs { background-position-x: -125px; }
            .facility.enquiries { background-position-x: -150px; }
            .facility.laundry { background-position-x: -175px; }
            .facility.wifi { background-position-x: -200px; }
            .facility.disability { background-position-x: -225px; }
            .facility.pets { background-position-x: -250px; }

            .tpl-section-wrapper.html h1 {
                font-weight: bold;
            }

            .tpl-section-wrapper.html p {
                margin: 0 0 10px 0;
                font-size: 14px;
                line-height: 1.3em;
            }

            .tpl-section-wrapper.html p:last-child {
                margin-bottom: 0;
            }

            .berthingAttr { font-size: 16px; margin-bottom: 5px; }
            .berthingAttr:last-child { margin-bottom: 0; }

        </style>

        <link type="text/css" rel="stylesheet" href="/application/layout/global.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" id="mapStyle" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div class="wrapper">

            <div id="header">
                <div style="margin: 12px 0 0 20px;">
                    <a href="http://<?= DOMAIN ?>"><img src="/application/images/logo.png" /></a>
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
                            <div style="margin-bottom: 1px;">
                                <a class="findNearest" href="" data-sub="gasstation">
                                    Gas Station
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
                                                        <a href="/<?= $nearby['id'] ?>">
                                                            <img src="/application/images/open_in_new_window_small.png" />
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

                    <div style="position: relative; width: 580px; height: 200px; background-color: #f4f5f6; overflow: hidden; position: relative; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                        <? if ($mainPhotoInfo !== NULL): ?>
                            <img src="/data/photos/gallery/<?= $mainPhotoInfo['id'] ?>.jpg" style="position: absolute; bottom: -<?= $mainPhotoInfo['offset'] ?>px;"/>
                        <? else: ?>
                            <div style="width: 64px; height: 52px; background-image: url('/application/images/camera.png'); margin: 72px auto 0;"></div>
                        <? endif; ?>
                    </div>

                    <div class="tpl-section" style="position: relative; border-radius: 0 0 2px 2px; z-index: 999;">
                        <div class="tpl-section-wrapper">

                            <div style="float: right;">

                                <?php
                                $facilities = [
                                    'water' => 'Water',
                                    'electricity' => 'Electricity',
                                    'showers' => 'Showers',
                                    'toilets' => 'Toilets',
                                    'waste' => 'Waste Disposal',
                                    'customs' => 'Customs',
                                    'enquiries' => 'Tourist Info',
                                    'laundry' => 'Laundry',
                                    'wifi' => 'WiFi',
                                    'disability' => 'Disability Access',
                                    'pets' => 'Pets'
                                ];
                                ?>

                                <? foreach ($facilities AS $facility => $name): ?>
                                    <? if (isset($attrs->facilities->{$facility}) && $attrs->facilities->{$facility}->value == 'yes'): ?>
                                        <span class="facility <?= $facility ?>" title="<?= $name ?>"></span>
                                    <? endif; ?>
                                <? endforeach; ?>

                            </div>

                            <div style="font-size: 18px; font-weight: bold; color: #444; margin: 4px 0 6px 0;">
                                <?= $poi->name ?> (<?= $subsMap[$poi->sub] ?>)
                            </div>
                            <div style="font-size: 14px;">
                                <?= $poi->latLng->latFormatted() . " - " . $poi->latLng->lngFormatted() ?>
                            </div>

                        </div>
                    </div>

                    <? if ($poi->cat === 'berthing' && isset($attrs->berthing)): ?>
                        <div class="tpl-section">
                            <div class="tpl-section-wrapper html" style="font-style: italic;">
                                <? if ($attrs->berthing->assistance->value === 'yes'): ?>
                                    <div class="berthingAttr">Berthing with assistance</div>
                                <? endif; ?>
                                <? if (isset($attrs->berthing->maxdraught->value) && $attrs->berthing->maxdraught->value !== ''): ?>
                                    <div class="berthingAttr">Max draught: <?= $attrs->berthing->maxdraught->value ?><?= $attrs->berthing->maxdraught->type ?></div>
                                <? endif; ?>
                                <? if (isset($attrs->berthing->maxlength->value) && $attrs->berthing->maxlength->value !== ''): ?>
                                    <div class="berthingAttr">Max length: <?= $attrs->berthing->maxlength->value ?><?= $attrs->berthing->maxlength->type ?></div>
                                <? endif; ?>
                                <? if (isset($attrs->berthing->seaberths->value)): ?>
                                    <div class="berthingAttr">Berths number: <?= $attrs->berthing->seaberths->total->value ?></div>
                                <? endif; ?>
                                <? if (isset($attrs->berthing->type) && count($attrs->berthing->type->values) > 0): ?>
                                    <div class="berthingAttr">
                                        <?php
                                        $typeMap = [
                                            'bowto' => 'bow-to',
                                            'sternto' => 'stern-to',
                                            'lazyline' => 'lazyline',
                                            'alongiside' => 'alongside'
                                        ];
                                        ?>
                                        Berthing type:
                                        <? for ($i = 0; $i < count($attrs->berthing->type->values); $i++): ?>
                                            <span>
                                                <?= $typeMap[$attrs->berthing->type->values[$i]] ?><? if ($i < count($attrs->berthing->type->values) - 2): ?>,<? elseif ($i == count($attrs->berthing->type->values) - 2): ?> or<? endif; ?>
                                            </span>
                                        <? endfor; ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    <? endif; ?>

                    <? if (isset($attrs->description) && $attrs->description->details !== ''): ?>
                        <div class="tpl-section">
                            <div class="tpl-section-wrapper html">
                                <?= html($attrs->description->details) ?>
                            </div>
                        </div>
                    <? endif; ?>

                    <? if (isset($attrs->approach) && $attrs->approach->details !== ''): ?>
                        <div class="tpl-section">
                            <div class="tpl-section-wrapper html">
                                <h1>Approach</h1>
                                <?= html($attrs->approach->details) ?>
                            </div>
                        </div>
                    <? endif; ?>

                </div>

                <div style="clear: both;"></div>

            </div>

            <div id="footer">
                <div class="footer-content">
                    Pocketsail &copy; 2015, with <img src="/application/images/love.png" style="vertical-align: bottom;"/> from London.
                </div>
            </div>

        </div>

    </body>
</html>