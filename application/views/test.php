<!DOCTYPE html>
<html>

    <head>
        <title>PocketSail - main page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="/application/js/controllers/Service.js"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/jquery/utils.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-mousewheel.js"></script>
        <script src="/application/js/jquery/scrollbar.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/map-style.js"></script>
        <link href="/application/js/jquery/scrollbar.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            $(function() {
                
                var map = null;
                google.maps.visualRefresh = true;
                var styledMap = new google.maps.StyledMapType(psMapStyles, {name: "PocketSail"});
                
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 8,
                    center: new google.maps.LatLng(43.5, 17),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoomControlOptions: { position: google.maps.ControlPosition.TOP_RIGHT },
                    streetViewControl: false,
                    panControl: false,
                    mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
                    }
                });
                
                map.mapTypes.set('map_style', styledMap);
                map.setMapTypeId('map_style');
                
                $('#leftPane').mCustomScrollbar({
                    mouseWheelPixels: 40,
                    //autoHideScrollbar: true,
                    scrollInertia: 0,
                    advanced:{
                        updateOnContentResize: true
                    }
                });
                
                $('#info-content-wrapper').mCustomScrollbar({
                    mouseWheelPixels: 40,
                    //autoHideScrollbar: true,
                    scrollInertia: 0,
                    advanced:{
                        updateOnContentResize: true
                    }
                });
                
                $('img').click(function() {
                    $('#menu').height(100);
                    $('#leftPane').css('top', 100);
                    $('#leftPane').css('bottom', 0);
                });
                
                $('.view-button').click(function(e) {
                    e.preventDefault();
                    $('.view-button').removeClass('on');
                    $(this).addClass('on');
                });
                
                var mappreview = new google.maps.Map(document.getElementById('map-preview'), {
                    zoom: 8,
                    center: new google.maps.LatLng(43.5, 17),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoomControlOptions: { position: google.maps.ControlPosition.TOP_RIGHT },
                    streetViewControl: false,
                    panControl: false,
                    mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
                    }
                });
                
                mappreview.mapTypes.set('map_style', styledMap);
                mappreview.setMapTypeId('map_style');
                
                $('.info-menu-button').click(function(e) {
                    e.preventDefault();
                });
                
                //                $('#target').dblclick(function() {
                //                    Service.place_info({
                //                        success: function(res) {
                //                            var infoHeader = $(res).find('#info-header').html();
                //                            var summary = $(res).find('#summary').html();
                //                            var fullView = $(res).find('#full-view').html();
                //                            $('#info-header').html(infoHeader);
                //                            $('#info-content').html(summary);
                //                            $('#info-window').show();
                //                        }
                //                    });
                //                });
                
                $('.info-card-wrapper').click(function() {
                    $('.info-card-wrapper').removeClass('active');
                    $(this).addClass('active');
                });
                
                $('.info-card-button').click(function(e) {
                    e.preventDefault();
                    var action = $(this).attr('href');
                    if (action === 'details') {
                        Service.place_info({
                            success: function(res) {
                                var infoHeader = $(res).find('#info-header').html();
                                var summary = $(res).find('#summary').html();
                                var fullView = $(res).find('#full-view').html();
                                $('#info-header').html(infoHeader);
                                $('#info-content').html(summary);
                                $('#info-window').show();
                                google.maps.event.trigger(mappreview, 'resize');
                            }
                        });
                    }
                    if (action === 'hide') {
                        var elem = $(this).closest('.info-card-wrapper');
                        elem.removeClass('active');
                        elem.animate({
                            height: 0
                        }, 200, function() {
                            elem.remove();
                        });
                    }
                });
            });
        </script>
        <style type="text/css">

            body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Verdana, Arial, sans-serif; color: #333; }
            input { outline: none; }
            .header { position: absolute; z-index: 1; height: 45px; background-color: #f1f2f3; width: 100%; min-width: 1350px; box-shadow: 0 0 2px rgba(0, 0, 0, 0.5); }
            .mainWrapper { width: 100%; height: 100%; background-color: #fff; }
            .mapWrapper { position: absolute; top: 45px; bottom: 0; width: 100%; min-width: 1350px; min-height: 870px; background-color: #aaa; }
            .menu-button { cursor: pointer; width: 45px; text-align: center; border-radius: 2px 0 0 2px; box-shadow: 0px 1px 1px rgba(120, 120, 120, 0.1); }
            .menu-button.on { background-color: #f4f5f6; box-shadow: 0 0 1px rgba(0, 0, 0, 0.3) inset; }

            .view-menu { margin-left: 7px; }
            a.view-button { position: relative; cursor: pointer; display: block; float: left; padding: 5px 7px; color: #444; width: 45px; text-align: center; text-decoration: none; box-shadow: 0px 1px 1px rgba(120, 120, 120, 0.1); background-color: #eaebec; text-shadow: rgb(240, 240, 240) 0px 1px 0px; border: solid 1px #c0c1c2; font-family: 'Helvetica Neue', Helvetica, Verdana, Arial, sans-serif; font-size: 11px; font-weight: bold; }
            a.view-button.on { background-color: #f5f6f7; box-shadow: 0 0 1px rgba(0, 0, 0, 0.2) inset; }
            a.view-button:hover { z-index: 9999; border-color: #a6a7a8; box-shadow: 0px 1px 1px rgba(120, 120, 120, 0.2); }
            a.view-button.on:hover { border-color: #c0c1c2 !important; box-shadow: 0 0 1px rgba(0, 0, 0, 0.2) inset; }

        </style>
    </head>

    <body>

        <div class="mainWrapper">

            <!-- Header -->
            <div class="header">
            </div>

            <!-- Map -->
            <div class="mapWrapper">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>

            <!-- Left column -->
            <div style="position: absolute; left: 30px; z-index: 2; width: 420px; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);">

                <div id="menu" style="position: relative; height: 100px; background-color: #e1e1e2; background-image: url('/application/images/back.png'); background-repeat: repeat-x;">
                    <div style="border-bottom: solid 1px #d6d7d8;">
                        <div style="position: relative; float: right; margin: 11px 10px;">
                            <a href="" style="position: absolute; top: 7px; right: 7px; background-image: url('/application/images/search-button.png'); width: 16px; height: 16px;"></a>
                            <input type="text" style="margin: 0; border: solid 1px #d5d6d7; font-size: 15px; padding: 6px; width: 242px; border-radius: 3px;" />
                        </div>
                        <img src="/application/images/logo.png" style="margin: 13px 0 9px 11px;" />
                    </div>
                    <div style="border-top: solid 1px #e9eaeb; padding: 5px 3px;">
                        <div>
                            <div class="view-menu" style="margin-top: 4px;">
                                <a href="history" class="view-button on" style="border-radius: 3px 0 0 3px;">History</a>
                                <a href="search" class="view-button" style="margin-left: -1px;">Search</a>
                                <a href="routes" class="view-button" style="margin-left: -1px;">Routes</a>
                                <a href="places" class="view-button" style="margin-left: -1px; border-radius: 0 3px 3px 0;">Places</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="leftPane" style="position: absolute; left: 30px; top: 98px; width: 420px; bottom: 0; overflow: hidden; min-height: 817px;">
                <div style="background-color: rgba(0, 0, 0, 0.25);">
                    <div style="">

                        <div style="height: 1px; margin-bottom: 10px;"></div>

                        <style>

                            .info-card-wrapper { overflow: hidden; width: 396px; height: 170px; margin: 10px auto; background-color: #f0f1f2; border-radius: 2px; box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); }
                            .info-card-wrapper.active { margin: -2px auto; border: solid 2px #4ea5d4; }
                            .info-card .icon { float: left; width: 26px; height: 29px; }
                            .info-card .icon.marina { background-image: url('/application/images/info-icons/marina-small.png'); }
                            .info-card .basic { margin-left: 34px; }
                            .info-card .basic .name { font-size: 14px; font-weight: bold; }
                            .info-card .basic .location { font-size: 11px; margin-top: 1px; }
                            .info-card .content { height: 85px; background-color: #fff; border-bottom: solid 1px #e0e1e2; border-top: solid 1px #e0e1e2; font-size: 12px; }

                            table.list { border-collapse: collapse; }
                            table.list td { padding-bottom: 0; }

                            a.info-card-button { box-shadow: 0px 1px 1px rgba(120, 120, 120, 0.1); text-decoration: none; font-size: 11px; font-weight: bold; color: #333; background-color: #eaebec; border: solid 1px #c0c1c2; padding: 2px 5px; border-radius: 3px; }
                            a.info-card-button:hover { box-shadow: 0px 1px 1px rgba(120, 120, 120, 0.2); border-color: #a0a1a2; }
                            a.info-card-button:active { background-color: #f0f1f2; box-shadow: none; border-color: #c0c1c2; }

                        </style>

                        <div class="info-card-wrapper" id="target">
                            <div class="info-card" style="width: 100%; height: 100%;">
                                <div style="height: 45px;">

                                    <div style="float: right; padding: 7px;">
                                        <img src="/application/images/zadar-small.png" style="border: solid 1px #e0e1e2; background-color: #fff; padding: 3px; border-radius: 3px;" />
                                    </div>

                                    <div style="padding: 9px;">
                                        <div class="icon marina"></div>
                                        <div class="basic">
                                            <div class="name">ACI Marina Palmežana</div>
                                            <div class="location">44°17'.32N 017°13'.23E</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content">
                                    <div style="padding: 10px;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam.</div>
                                </div>
                                <div>
                                    <div class="view-menu" style="margin-top: 6px; float: right; margin-right: 6px;">
                                        <a href="details" class="info-card-button" style="margin-right: 2px;">Details</a>
                                        <a href="hide" class="info-card-button">Hide</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-card-wrapper">
                            <div class="info-card" style="width: 100%; height: 100%;">

                                <div style="height: 45px;"> 

                                    <div style="padding: 9px;">
                                        <div class="icon marina"></div>
                                        <div class="basic">
                                            <div class="name">Anchorage</div>
                                            <div class="location">44°17'.32N 017°13'.23E</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="content">
                                    <div style="padding: 10px;">
                                        <table class="list">
                                            <tr>
                                                <td style="font-weight: bold; color: #999; text-align: right; padding-right: 5px;">Depth</td>
                                                <td>5-10m</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; color: #999; text-align: right; padding-right: 5px;">Sea bed</td>
                                                <td>sand</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; color: #999; text-align: right; padding-right: 5px;">Holding</td>
                                                <td>good</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; color: #999; text-align: right; padding-right: 5px;">Max.length</td>
                                                <td>25m</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div>
                                    <div class="view-menu" style="margin-top: 6px; float: right; margin-right: 6px;">
                                        <a href="details" class="info-card-button" style="margin-right: 2px;">Details</a>
                                        <a href="hide" class="info-card-button">Hide</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="info-card-wrapper"></div>
                        <div class="info-card-wrapper"></div>
                        <div class="info-card-wrapper"></div>
                        <div class="info-card-wrapper"></div>
                        <div class="info-card-wrapper"></div>
                        <div class="info-card-wrapper"></div>

                        <div style="height: 1px; margin-top: 10px;"></div>

                    </div>
                </div>
            </div>

            <style>
                #info-window { display: none; position: absolute; top: 80px; bottom: 0; right: 0; left: 450px; }
                h1 { font-size: 19px; margin-bottom: 7px; }
                h2 { font-size: 14px; color: #666; }
                .gallery { margin-bottom: 0px; width: 550px; height: 113px; }
                .gallery .ginner div { width: 102px; height: 102px; float: left; margin-right: 8px; background-size: 190px 120px; border-radius: 3px; box-shadow: 0 1px 3px 0px rgba(0, 0, 0, .25); }
                .inner { padding: 10px; color: #333; }
                table td { padding-bottom: 4px; }
                a.nearby { display: block; text-decoration: none; color: #3079ed; font-weight: normal; font-size: 14px; margin-bottom: 3px; }
                a.menu { float: left; padding: 7px 13px 0 13px; display: block; border-right: solid 1px #e0e1e2; height: 24px; text-decoration: none; color: #444; font-size: 12px; font-weight: bold; }
            </style>

            <div id="info-window">
                <div style="margin: 0 95% 0 5%; width: 850px; height: 800px; background-color: #f0f1f2; border-radius: 3px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);">
                    <div style="padding: 20px;">

                        <div style="float: right; width: 260px;">
                            <div id="map-preview" style="width: 258px; height: 258px; border: solid 1px #d7d8d9; border-bottom-width: 2px; border-radius: 3px;"></div>
                            <div class="contact-block" style="margin-top: 20px; ">
                                <h2>Contact</h2>
                                <table style="font-size: 13px;">
                                    <tr>
                                        <td style="color: #aaa; font-weight: bold; text-align: right; padding-right: 10px;">www</td>
                                        <td>http://www.zadar.com</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #aaa; font-weight: bold; text-align: right; padding-right: 10px;">email</td>
                                        <td>marina@zadar.com</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #aaa; font-weight: bold; text-align: right; padding-right: 10px;">tel</td>
                                        <td>+34 567 123 123</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="nearby-block" style="margin-top: 10px; border-top: solid 1px #e0e1e2;">
                                <div style="border-top: solid 1px #f8f9fa;"></div>
                                <h2>Nearby</h2>
                                <a class="nearby">Things to do &#8250;</a>
                                <a class="nearby">Maintenance &#8250;</a>
                                <a class="nearby">Nautical Services &#8250;</a>
                            </div>
                        </div>

                        <div style="position: relative; float: left; width: 530px; height: 760px;">
                            <div style="height: 140px;">
                                <div style="width: 126px; height: 126px; background-color: #000; background-image: url('/application/images/zadar1.jpg');  background-size: 168px 128px; margin-right: 6px; float: left; border-radius: 3px; border: solid 1px #c0c1c2;"></div>
                                <div style="width: 126px; height: 126px; background-color: #000; background-image: url('/application/images/zadar2.jpg');  background-size: 168px 128px; margin-right: 6px; float: left; border-radius: 3px; border: solid 1px #c0c1c2;"></div>
                                <div style="width: 126px; height: 126px; background-color: #000; background-image: url('/application/images/zadar3.jpg');  background-size: 168px 128px; margin-right: 6px; float: left; border-radius: 3px; border: solid 1px #c0c1c2;"></div>
                                <div style="width: 126px; height: 126px; background-color: #000; background-image: url('/application/images/zadar1.jpg');  background-size: 168px 128px; float: left; border-radius: 3px; border: solid 1px #c0c1c2;"></div>
                            </div>
                            <div style="position: relative; height: 612px; background-color: #fff; border: solid 1px #e0e1e2; border-bottom-width: 2px; border-radius: 3px;">

                                <style>
                                    #info-header { padding: 15px; }
                                    #info-header .icon { float: left; width: 65px; height: 73px; background-repeat: no-repeat; }
                                    #info-header .icon.marina { background-image: url("/application/images/info-icons/marina-large.png"); }
                                    #info-header .basic { margin-left: 76px; }
                                    #info-header .basic .name { font-size: 22px; }
                                    #info-header .basic .location { font-size: 15px; }
                                    #info-header .basic .author { margin-top: 8px; font-size: 12px; }
                                </style>

                                <div id="info-header">
                                    <!--
                                    <div class="icon marina" style="float: left; width: 65px;"></div>
                                    <div class="basic">
                                        <div class="name">Marina Zadar</div>
                                        <div class="location">44°17'.32N 017°13'.23E</div>
                                        <div class="author"><span style="color: #999;">created by</span> David Stefan (skipper)</div>
                                    </div>
                                    -->
                                </div>

                                <style>

                                    .info-menu { position: relative; z-index: 1; border: solid 1px #e0e1e2; border-left: none; border-right: none; background-color: #f0f1f2; height: 28px; }

                                    a.info-menu-button { position: relative; z-index: 4; text-decoration: none; font-weight: bold; display: block; border: solid 1px #e0e1e2; height: 28px; float: left; margin: -1px -1px -1px 0; }
                                    a.info-menu-button:hover { z-index: 99999; border-color: #c0c1c2; }
                                    a.info-menu-button.selected { background-color: #f5f6f7; box-shadow: 0 0 2px rgba(0, 0, 0, 0.05) inset; }
                                    a.info-menu-button.selected:hover { border-color: #e0e1e2; cursor: default; }

                                    a.info-menu-button .label { display: block; padding: 6px 12px 0px; font-size: 12px; color: #444; }
                                    a.info-menu-button .label .details { font-size: 11px; font-weight: normal; }

                                    a.info-menu-button.selected .label { height: 29px; background-image: url('/application/images/info-menu-arrow.png'); background-repeat: no-repeat; background-position: 50% 23px; z-index: 9999; } 

                                </style>

                                <div class="info-menu">
                                    <a class="info-menu-button selected" href="" style="margin-left: -1px;">
                                        <span class="label">Summary</span>
                                    </a>
                                    <a class="info-menu-button" href="">
                                        <span class="label">Comments <span class="details">(23)</span></span>
                                    </a>
                                    <a class="info-menu-button" href="">
                                        <span class="label">Questions <span class="details">(6)</span></span>
                                    </a>
                                    <a class="info-menu-button" href="">
                                        <span class="label">Full View <span class="details"></span></span>
                                    </a>
                                </div>

                                <style>
                                    #info-content-wrapper { position: absolute; left: 0; right: 0; top: 132px; bottom: 0; }
                                    #info-content { padding: 15px; line-height: 1.3em; font-size: 15px; }
                                </style>

                                <div id="info-content-wrapper">
                                    <div id="info-content">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--
            <div class="info-window">

                <div style="float: right; width: 242px; height: 500px;">
                    <div style="background-image: url('/application/images/map.jpg'); width: 242px; height: 242px; box-shadow: 0 1px 3px 0px rgba(0, 0, 0, .4); border-radius: 3px;">
                    </div>
                    <div style="margin-top: 20px; font-size: 15px; font-weight: bold; color: rgb(66, 66, 66);">
                        Contact
                        <div>
                            <table style="border-collapse: collapse; font-weight: normal; margin-top: 10px; font-size: 13px;">
                                <tr>
                                    <td style="text-align: right; padding-right: 5px; color: #999; font-weight: bold;">www</td>
                                    <td>http://www.zadar.com</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; padding-right: 5px; color: #999; font-weight: bold;">email</td>
                                    <td>marina@zadar.com</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; padding-right: 5px; color: #999; font-weight: bold;">tel</td>
                                    <td>+34 678 123 123</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; padding-right: 5px; color: #999; font-weight: bold;">fax</td>
                                    <td>+34 678 123 123/2</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="border-top: solid 1px #e0e1e2; margin-top: 13px;"></div>
                    <div style="border-top: solid 1px #f6f7f8; font-size: 15px; font-weight: bold; color: rgb(66, 66, 66); padding-top: 10px;">
                        Nearby
                        <div style="padding-top: 7px;">
                            <a class="nearby" href="#">Things to do &#8250;</a>
                            <a class="nearby" href="#">Maintenance &#8250;</a>
                            <a class="nearby" href="#">Nautical services &#8250;</a>
                            <a class="nearby" href="#">Transport &#8250;</a>
                        </div>
                    </div>
                </div>

                <div style="float: left; ">

                    <div class="gallery">
                        <div class="ginner">
                            <div style="background-image: url('/application/images/zadar1.jpg');"></div>
                            <div style="background-image: url('/application/images/zadar2.jpg');"></div>
                            <div style="background-image: url('/application/images/zadar3.jpg');"></div>
                            <div style="background-image: url('/application/images/zadar2.jpg');"></div>
                            <div style="background-image: url('/application/images/zadar1.jpg');" style="margin-right: 0;"></div>
                        </div>
                    </div>

                    <div style="width: 542px; height: 637px; background-color: #fff; border-radius: 3px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
                        <div class="inner">
                            <div style="width: 41px; height: 46px; background-image: url('/application/images/icon.png'); float: left; margin-top: 2px;"></div>
                            <div style="margin-left: 50px;">
                                <div style="font-size: 26px;">Marina Zadar</div>
                                <div style="font-size: 13px; font-weight: bold; margin-left: 1px;">44°17'.32N 017°13'.23E</div>
                            </div>
                        </div>
                        <div style="margin-top: 2px; border-top: solid 1px #e0e1e2; border-bottom: solid 1px #e0e1e2; height: 30px; background-color: #f6f7f8;">
                            <a class="menu" href="#" style="">Summary</a>
                            <a class="menu" href="#">Comments (23)</a>
                            <a class="menu" href="#">Questions (3)</a>
                            <a class="menu" href="#">Full view</a>
                        </div>
                        <div style="padding: 15px; line-height: 1.4em; font-size: 14px;">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum...
                        </div>
                    </div>

                </div>

            </div>
            -->

        </div>

    </div>

</body>
</html>
