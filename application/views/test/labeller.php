<html>
    <head>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/Label.js"></script>
    </head>
    <body style="width: 10000px;">
        <script>
            $(function() {

//                var projector = new Projector();
//                var pos1 = new Position(218, 155);
//                var pos2 = new Position(243, 164);
//                var name1 = "Kornat";
//                var name2 = "Sukosan";
//                var desc1 = buildLabelDescriptor(name1, "il4,-20,0flbntn0");
//                var desc2 = buildLabelDescriptor(name2, "il4,-20,0flbntn0");
//
//                var l1 = new Label(2, name1, '', '', null, pos1, desc1);
//                var l2 = new Label(6, name2, '', '', null, pos2, desc2);
//                
//                l2.eliminateOverlaps(l1);
//                
//                drawShape($('#map'), l1);
//                drawShape($('#map'), l2);
//                
//                return;

                var zoom = 1;
                var mapWidth = Math.pow(2, zoom) * 256;
                var names = ["Palmezana", "Sv.Klement", "Hvar", "Kornat", "Zadar", "Sukosan"];
                //var descs = ["il4,-20,0pm20,20flbnrlbtn15", "im3,-26,0fmbnrbtn10", "is3,-26,0ps10,10fsnnrbtn5"];
                var descs = ["im3,-70,0ps-84,0tlbirltbn7"];
                var projector = new Projector();
                var labels = [];

                $('#map').width(mapWidth);
                $('#map').height(mapWidth);

                for (var i = 0; i < 2000; i++) {
                    var name = names[Math.floor(Math.random() * names.length)];
                    var descString = descs[Math.floor(Math.random() * descs.length)];
                    var desc = buildLabelDescriptor(name, descString);
                    var lat = 170 * Math.random() - 85;
                    var lng = 360 * Math.random() - 180;
                    var latLng = new LatLng(lat, lng);
                    var pos = projector.mercator(latLng, zoom);
                    labels.push(new Label(i, name, 'berthing', 'marina', latLng, pos, desc));
                }

                for (var i = 0; i < labels.length - 1; i++) {
                    var master = labels[i];
                    for (var j = i + 1; j < labels.length; j++) {
                        var slave = labels[j];
                        master.eliminateOverlaps(slave);
                        if (!slave.isVisible()) {
                            labels.splice(j--, 1);
                        }
                    }
                }

                for (var i = 0; i < labels.length; i++) {
                    //console.log(labels[i]);
                    drawShape($('#map'), labels[i]);
                }
                return;

                var desc = buildLabelDescriptor(name, "il4,-20,0ps-34,0flbnrlbtn10");
                var latLng = new LatLng(0, 0);
                var projector = new Projector();
                var pos = projector.mercator(latLng, zoom);

                var l1 = new Label(1, name, "berthing", "marina", latLng, pos, desc);
                var l2 = new Label(2, name, "berthing", "marina", latLng, pos, desc);

                l1.eliminateOverlaps(l2);
                drawShape($('#map'), name, l2.getRenderDescriptor(), pos);
//                l1.eliminateOverlaps(l2);
//                console.log(l2.getRenderDescriptor());
//                var shape = buildPersistentShape(desc, pos);

//                drawShape($('#c1'), l2.varShapes[0], name);
//                drawShape($('#c2'), l2.varShapes[1], name);
//                drawShape($('#c2'), shapes[2], name);
//                drawShape($('#c3'), shapes[3], name);
//                drawShape($('#c4'), shapes[4], name);

                // 13px normal 6.1, bold 6.7
                // 11px normal 5, bold 5.8
                // 9px normal 4.5, bold 4.8
            });
        </script>
        <style>
            .anchor { position: absolute; }
            .icon { z-index: 999; position: absolute; background-image: url('/application/images/icon.png'); }
            .icon.p { z-index: 998; opacity: 0.5; }
            .icon.s { width: 6px; height: 6px; }
            .icon.m { width: 14px; height: 14px; }
            .icon.l { width: 17px; height: 17px; }
            .icon.x { width: 25px; height: 25px; }
            .text { z-index: 999; position: absolute; font-family: Arial; white-space: nowrap; text-shadow: -1px -1px 0 rgba(255, 255, 255, 0.5), 1px -1px 0 rgba(255, 255, 255, 0.5), -1px 1px 0 rgba(255, 255, 255, 0.5), 1px 1px 0 rgba(255, 255, 255, 0.5); }
            .text.s { font-size: 9px; line-height: 9px; }
            .text.m { font-size: 11px; line-height: 11px; }
            .text.l { font-size: 13px; line-height: 13px; }
            .text.b { font-weight: bold; }
            .text.i { font-style: italic; }
        </style>

        <div id="map"></div>
    </body>
</html>