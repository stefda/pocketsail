<!DOCTYPE html>
<html>

    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>-->
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>

        <style type="text/css">
            html, body { width: 100%; height: 100%; }
        </style>

        <script>

            $(function() {

                var rows = 10000;
                var cols = 10000;
                var map = [];
                
                for (var i = 0; i < cols; i++) {
                    for (var j = 0; j < rows; j++) {
                        if (j === 0) {
                            map[i] = [];
                        }
                        map[i][j] = "David";
                    }
                }

//                function rect(ctx, path) {
//                    ctx.fillStyle = "#FF0000";
//                    ctx.beginPath();
//                    ctx.moveTo(path[0][0], path[0][1]);
//                    for (var i = 1; i < path.length; i++) {
//                        ctx.lineTo(path[i][0], path[i][1]);
//                    }
//                    ctx.closePath();
//                    ctx.fill();
//                }
//
//                var c = document.getElementById("canvas");
//                var ctx = c.getContext("2d");
//                
//                var path = [[10, 10], [20, 10], [20, 40], [35, 50], [10, 100]];
//                
//                rect(ctx, path);
            });

        </script>

    </head>
    <body>

        <canvas id="canvas" width="500" height="500"></canvas>

    </body>
</html>
