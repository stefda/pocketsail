<!DOCTYPE html>
<html>
    <head>
        <title>Menu</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="/application/js/jquery/jquery.js"></script>

        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

        <style>
            body, html { width: 100%; height: 100%; }
            body { margin: 0; padding: 0; }
            .loader { background-color: #f0f1f2 !important; border-color: #e0e1e2 !important; color: #d0d1d2 !important; }
        </style>

        <script>

//            var focusedMarker = null;

            $(function() {
                
                var a = [];
                
                var o1 = {};
                var o2 = {};
                var o3 = {};
                
                for (var i = 0; i < 3000; i++) {
                    a[i] = [];
                }
                
                var start = new Date().getMilliseconds();
                
                for (var i = 0; i < 3000; i++) {
                    for (var j = 0; j < 3000; j++) {
                        a[i][j] = 234;
                    }
                }
                
                var end = new Date().getMilliseconds();
                console.log(end - start);

//                var map = new Map({
//                    canvas: 'canvas',
//                    cursor: 'crosshair',
//                    cache: true
//                });
            });

        </script>
    </head>
    <body oncontextmenu="return false;">

        <div id="head" style="z-index: 9999; width: 100%; height: 60px; background-color: #e9eaeb; position: fixed; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">
            <div style="float: right; margin: 15px 20px 0 0;">
                <input id="labellingButton" class="tpl-button tpl-button-blue" type="button" value="Do labelling" style="margin-left: 10px;" />
                <input id="indexingButton" class="tpl-button tpl-button-blue" type="button" value="Do indexing" style="margin-left: 10px;" />
            </div>
            <img src="/application/images/logo.png" style="margin: 14px 0 0 16px;" />
        </div>

        <div id="canvas" style="position: absolute; width: 100%; top: 60px; bottom: 0; height: auto;"></div>

        <style>
            .t-text { 
                font-size: 13px;
                white-space: nowrap;
                font-family: Arial;
                color: #000;
                cursor: default;
                text-shadow:
                    -1px -1px 0 rgba(255, 255, 255, 0.8),
                    1px -1px 0 rgba(255, 255, 255, 0.8),
                    -1px 1px 0 rgba(255, 255, 255, 0.8),
                    1px 1px 0 rgba(255, 255, 255, 0.8);
            }
            .t-icon {
                vertical-align: text-top;
                display: inline-block;
                width: 12px; height: 14px;
                background-image: url('/application/images/marker-icons.png');
            }
            .t-icon.anchor {
                background-position: -62px -121px;
            }
            .t-icon.buoy {
                background-position: -62px -151px;
            }
            .t-icon.marina {
                background-position: -240px -2px;
            }
        </style>

        <div style="position: absolute; top: 200px; left: 500px;">
            <span class="t-text">
                <span style="font-style: italic;">Luka Umag</span>
                <span class="t-icon marina"></span><span class="t-icon buoy"></span>
            </span>
        </div>

    </body>
</html>
