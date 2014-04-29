<!DOCTYPE html>
<html>
    <head>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <style>
            body { margin: 0; padding: 0; font-family: Arial; font-size: 13px; }
            .page { padding: 2px; margin: 0 0 10px 0; color: #333; line-height: 1.3em; cursor: default; }
            .page.selected { background-color: #f26500; border-radius: 3px; box-shadow: 0 2px 3px #555; }
            .page-inner { box-shadow: 0 2px 3px #555; }
            .page.hover .page-inner { box-shadow: 0 2px 3px #333; }
            .page.selected .page-inner { box-shadow: none; }
            .page-header { border-radius: 2px 2px 0 0; height: 50px; background-color: #f9f9f9; border-bottom: solid 1px #ededed; }
            .page-middle { height: 120px; background-color: #fff; }
            .page-footer { border-radius: 0 0 2px 2px; height: 30px; background-color: #f9f9f9; border-top: solid 1px #ededed; }
            .header { padding: 10px 13px 0 28px; font-size: 15px; font-weight: bold; color: #444; }
            .ctrl { position: absolute; left: 500px; }
        </style>
        <script>
            $(function() {

                var PSMapStyle = [
                    {
                        featureType: "water",
                        stylers: [
                            {
                                color: "#6dace3"
                            },
                            {
                                lightness: 0
                            }
                        ]
                    },
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [
                            {
                                visibility: "off"
                            },
                        ]
                    },
                    {
                        featureType: "road",
                        elementType: "labels",
                        stylers: [
                            {
                                visibility: "off"
                            },
                        ]
                    },
                    {
                        featureType: "transit.line",
                        elementType: "geometry",
                        stylers: [
                            {
                                visibility: "off"
                            }
                        ]
                    },
                    {
                        featureType: "transit.line",
                        elementType: "labels",
                        stylers: [
                            {
                                visibility: "off"
                            }
                        ]
                    },
                    {
                        featureType: "administrative.province",
                        elementType: "geometry",
                        stylers: [
                            {
                                visibility: "off"
                            }
                        ]
                    },
                    {
                        featureType: "administrative.province",
                        elementType: "labels",
                        stylers: [
                            {
                                visibility: "off"
                            }
                        ]
                    }
                ];

                var map = null;

                google.maps.visualRefresh = true;

                map = new google.maps.Map(document.getElementById('map'), {
                    mapTypeControlOptions: {
                        mapTypeIds: ['pocketsail', google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN, google.maps.MapTypeId.SATELLITE]
                    },
                    zoom: 8,
                    center: new google.maps.LatLng(43.5, 17),
                    mapTypeId: 'pocketsail'
                });

                map.mapTypes.set('pocketsail', new google.maps.StyledMapType(PSMapStyle, {
                    name: 'PocketSail',
                    title: 'Show PocketSail map'
                }));

                $('.page').click(function() {
                    $('.page').removeClass('selected');
                    $(this).addClass('selected');
                });

                $('.page').hover(function() {
                    $(this).addClass('hover');
                }, function() {
                    $(this).removeClass('hover');
                });
            });
        </script>
    </head>
    <body>

        <div style="position: fixed; top: 0; left: 0; height: 100%; width: 100%; background-color: green;">
            <div style="position: absolute; top: 45px; bottom: 0; width: 100%; background-color: red;">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>
        </div>

        <div style="box-shadow: 0 0 2px rgba(50, 50, 50, .8); top: 45px; left: 30px; position: absolute; width: 400px; margin: 0 0 0 0; padding-top: 8px">

            <div style="background-color: rgba(70, 70, 70, .3); padding: 10px 10px 1px 10px;">

                <div class="page">
                    <div class="page-inner">
                        <div class="page-header">
                            <div class="header">Uvala Hiljaca</div>
                        </div>
                        <div class="page-middle">
                            <div style="padding: 10px;">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent in lorem in mauris accumsan facilisis. Nullam vitae nulla lorem. 
                            </div>
                        </div>
                        <div class="page-footer"></div>
                    </div>
                </div>

                <div class="page selected">
                    <div class="page-inner">
                        <div class="page-header">
                            <div class="header">Marina Zadar (Tankerkomerc)</div>
                        </div>
                        <div class="page-middle">
                            <div style="padding: 10px;">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent in lorem in mauris accumsan facilisis. Nullam vitae nulla lorem. 
                            </div>
                        </div>
                        <div class="page-footer"></div>
                    </div>
                </div>

                <div class="page">
                    <div class="page-inner">
                        <div class="page-header">
                            <div class="header">Marina Dalmacija</div>
                        </div>
                        <div class="page-middle">
                            <div style="padding: 10px;">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent in lorem in mauris accumsan facilisis. Nullam vitae nulla lorem. 
                            </div>
                        </div>
                        <div class="page-footer"></div>
                    </div>
                </div>
                
                <div class="page">
                    <div class="page-inner">
                        <div class="page-header">
                            <div class="header">Marina Dalmacija</div>
                        </div>
                        <div class="page-middle">
                            <div style="padding: 10px;">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent in lorem in mauris accumsan facilisis. Nullam vitae nulla lorem. 
                            </div>
                        </div>
                        <div class="page-footer"></div>
                    </div>
                </div>
                
                <div class="page">
                    <div class="page-inner">
                        <div class="page-header">
                            <div class="header">Marina Dalmacija</div>
                        </div>
                        <div class="page-middle">
                            <div style="padding: 10px;">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent in lorem in mauris accumsan facilisis. Nullam vitae nulla lorem. 
                            </div>
                        </div>
                        <div class="page-footer"></div>
                    </div>
                </div>
                
                <div class="page">
                    <div class="page-inner">
                        <div class="page-header">
                            <div class="header">Marina Dalmacija</div>
                        </div>
                        <div class="page-middle">
                            <div style="padding: 10px;">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent in lorem in mauris accumsan facilisis. Nullam vitae nulla lorem. 
                            </div>
                        </div>
                        <div class="page-footer"></div>
                    </div>
                </div>

            </div>

        </div>

        <div style="position: fixed; top: 0; background-color: #f0f1f2; border-bottom: solid 1px #e3e4e5; height: 44px; width: 100%; box-shadow: 0px 1px 2px rgba(100, 100, 100, 0.5);">
        </div>

        <div style="position: fixed; top: 0; left: 30px; width: 400px; height: 53px; background-image: url('/application/images/back.png'); border-bottom: solid 1px #d6d7d8; box-shadow: 0 0 2px rgba(50, 50, 50, 0.5);">
            <div style="padding: 13px 0 0 11px;">
                <img src="/application/images/logo.png" />
            </div>
        </div>

    </body>
</html>