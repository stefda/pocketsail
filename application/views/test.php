<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script>
            
            var counter = (function() {
                var i = 0;
                return function() {
                    return ++i;
                };
            })();
            
            console.log(counter());
            console.log(counter());
            console.log(counter());
            
        </script>
    </head>
    <body>
    </body>
</html>
