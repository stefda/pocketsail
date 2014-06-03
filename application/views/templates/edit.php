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

                $('#test').select();

                $('a,input,textarea').focus(function() {
                    $(this).addClass('ps-ui-focus');
                });

                $('a,input,textarea').blur(function() {
                    $(this).removeClass('ps-ui-focus');
                });

                $('.text,.detailsText,.smallText').autosize();

                $('#saveButton').click(function() {
                    console.log(validator.validate());
                    var attrs = $('.attr:visible').serialize();
//                    var name = $('input[name=name]').val();
//                    var attrs = $('.attr').serialize();
                    TestBroker.post({
                        post: attrs,
//                        post: $.param({
//                            id: id,
//                            latLng: latLng.toWKT(),
//                            border: border === null ? null : border.toWKT(),
//                            name: name
//                        }) + '&' + attrs,
                        success: function(res) {
                            console.log(res);
                        }
                    });
                });
            });

        </script>

        <style>
            html, body { font-family: Arial; }
            a, input, textarea, select { outline: none; font-family: Arial; display: inline-block; margin: 0; }
            input { border: solid 1px #aaa; padding: 5px 7px; font-size: 14px; }
            h1 { font-size: 14px; margin-bottom: 4px; font-size: 14px; font-weight: bold; color: #555; }
            h2 { font-size: 12px; font-weight: normal; display: inline; margin-right: 8px; }
            .par { margin-bottom: 20px; }
            .text { width: 600px; height: 36px; resize: none; padding: 5px 7px; line-height: 1.4em; }
            .smallText { width: 300px; height: 18px; resize: none; padding: 5px 7px; line-height: 1.4em; }
            .detailsText { width: 600px; height: 18px; resize: none; padding: 5px 7px; line-height: 1.4em; }

        </style>

        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div style="width: 900px; margin: 20px auto;">

            <div class="par">
                <h1>
                    Name
                </h1>
                <input type="text" name="name" value="<?= $poi->name() ?>" />
            </div>

            <? include_view('templates/edit/berthing'); ?>

        </div>

    </body>
</html>