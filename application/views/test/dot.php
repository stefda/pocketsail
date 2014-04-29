<html>
    <head>
        <script src="/application/js/jquery/jquery.js"></script>
        <script>
            $(function() {
                $.ajax({
                    url: '/data/graph.xml',
                    success: function(res) {
                        var data = $(res);
                        data.find('.node').each(function() {
                            var node = $(this).find('ellipse');
                            var x = node.attr('cx');
                            var y = node.attr('cy');
                            if (x !== undefined && y !== undefined) {
                                var div = $('<div></div>');
                                $('body').append(div);
                                div.css('position', 'absolute');
                                div.css('left', parseInt(x) + 200 + 'px');
                                div.css('top', parseInt(y) + 200 + 'px');
                                div.css('width', 30 + 'px');
                                div.css('height', 30 + 'px');
                                div.css('border', 'solid 1px black');
                            }
                        });
                    }
                });
            });
        </script>
    </head>
    <body>

    </body>
</html>