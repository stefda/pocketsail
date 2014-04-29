<html>
    <head>
        <title>Templates testing</title>
        <meta charset="UTF-8" />
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>-->
        <script>
            $(function() {
                
                $('.detailsButton').click(function(e) {
                    e.preventDefault();
                    var details = $(this).parent().find('.details');
                    var places = $(this).parent().find('.places');
                    if (details.is(':visible')) {
                        details.hide();
                    }
                    else {
                        places.hide();
                        details.show();
                    }
                });
                
                $('.placesButton').click(function(e) {
                    e.preventDefault();
                    var places = $(this).parent().find('.places');
                    var details = $(this).parent().find('.details');
                    if (places.is(':visible')) {
                        places.hide();
                    }
                    else {
                        details.hide();
                        places.show();
                    }
                });
                
                $('.par').hover(
                function() {
                    $(this).find('.editButton').show();
                },
                function() {
                    $(this).find('.editButton').hide();
                });
            });
        </script>
        <style>

            body { font-size: 13px; font-family: Arial; color: #333; }
            h1 { margin-bottom: 3px; }
            h2 { margin-bottom: 3px; }
            h3 { margin-bottom: 2px; }
            ul { list-style: none; list-style-type: none; padding-left: 10px; }
            textarea { width: 400px; height: 40px; resize: none; font-family: Arial; }

            .details { display: none; }
            .places { display: none; }

            .status-nk { color: #aaa; }
            a.addInfoButton { text-decoration: none; background-color: #f0f1f2; border: solid 1px #e0e1e2; color: #333; padding: 0 3px; }
            
            .editButton { cursor: pointer; display: none; border: solid 1px #e0e1e2; border-radius: 1px; color: #b0b1b2; background-color: #fff; float: right; font-size: 10px; padding: 0 3px; margin-top: 7px; }

        </style>
    </head>
    <body>