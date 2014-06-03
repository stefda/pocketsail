<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/brokers/TestBroker.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>

        <script>

            function Validator() {

                this.valFxs = [];

                /**
                 * @param {Number} fx
                 */
                this.add = function(fx) {
                    this.valFxs.push(fx);
                };

                /**
                 * @returns {boolean}
                 */
                this.validate = function() {
                    var allValid = true;
                    for (var i = 0; i < this.valFxs.length; i++) {
                        var valid = this.valFxs[i]();
                        allValid = !allValid ? allValid : valid;
                    }
                    return allValid;
                };
            }

            var validator = new Validator();

            $(function() {
                
                $('a,input,textarea').focus(function() {
                    $(this).addClass('ps-ui-focus');
                });
                
                $('a,input,textarea').blur(function() {
                    $(this).removeClass('ps-ui-focus');
                });
                
                $('.text,.detailsText').autosize();
                
                $('#saveButton').click(function() {
                    console.log(validator.validate());
//                    var name = $('input[name=name]').val();
//                    var attrs = $('.attr').serialize();
//                    APIBroker.addPoi({
//                        post: $.param({
//                            id: id,
//                            latLng: latLng.toWKT(),
//                            border: border === null ? null : border.toWKT(),
//                            name: name
//                        }) + '&' + attrs,
//                        success: function(res) {
//                            console.log(res);
//                        }
//                    });
                });
            });

        </script>

        <style>
            html, body { font-family: Arial; }
            a, input, textarea { outline: none; font-family: Arial; }
            h1 { font-size: 14px; margin-bottom: 4px; font-size: 14px; font-weight: bold; color: #555; }
            h2 { font-size: 12px; font-weight: normal; display: inline; margin-right: 8px; }
            .par { margin-bottom: 20px; }
            .text { width: 600px; height: 36px; resize: none; padding: 5px 7px; line-height: 1.4em; }
            .detailsText { width: 600px; height: 18px; resize: none; padding: 5px 7px; line-height: 1.4em; }
        </style>

        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div style="width: 900px; margin: 20px auto;">
            <? include_view('berthing'); ?>
        </div>

    </body>
</html>