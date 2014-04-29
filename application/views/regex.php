<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="/application/js/jquery/jquery.js"></script>
    </head>
    <body>
        <script type="text/javascript">
            $(function() {

                function microtime(get_as_float) {
                    var unixtime_ms = new Date().getTime();
                    var sec = parseInt(unixtime_ms / 1000);
                    return get_as_float ? (unixtime_ms / 1000) : (unixtime_ms - (sec * 1000)) / 1000 + ' ' + sec;
                }

                var pattern = /^(i([s|m|l])(\d*)?)?(f([s|m|l])([n|b])([n|i])([t|r|b|l]{0,4})(\d*)?)?$/;

//                var start = microtime(true);
//                for (var i = 0; i < 10000; i++) {
                    var res = pattern.exec("is3flbi10");
                    console.log(res);
                    size res[2]
//                }
//                console.log(microtime(true) - start);
            });
        </script>
    </body>
</html>