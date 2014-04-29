<html>
    <head>
        <script type="text/javascript" src="/application/js/jquery/jquery.js"></script>
    </head>
    <body>
        <script>
            $(function() {
                $.ajax({
                    url: "/data/artobject.txt",
                    success: function(res) {
                        clipboardData.setData("Text", res);
                    }
                });
//                if (window.clipboardData && clipboardData.setData) {
//                    clipboardData.setData("Text", "");
//                }
            });
        </script>
    </body>
</html>