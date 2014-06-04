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

                $('#multi').multiButton();

                $('a,input,textarea').focus(function() {
                    $(this).addClass('ps-ui-focus');
                });

                $('a,input,textarea').blur(function() {
                    $(this).removeClass('ps-ui-focus');
                });

                $('.text,.detailsText,.smallDetailsText,.smallText').autosize();

                $('.detailsButton').click(function(e) {
                    var elem = $(this).closest('.hasDetail');
                    if ($(this).hasClass('visible')) {
                        $(this).removeClass('visible');
                        elem.next().hide();
                    } else {
                        $(this).addClass('visible');
                        elem.next().show();
                    }
                    e.preventDefault();
                });

                $('.hasDetail').hover(function() {
                    $(this).find('.detailsButton').css('visibility', 'visible');
                }, function() {
                    if (!$(this).next().is(':visible')) {
                        $(this).find('.detailsButton').css('visibility', 'hidden');
                    }
                });

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
            html, body { font-family: Arial; background-color: #fff; }
            body { overflow-y: scroll; }
            a, input, textarea, select { outline: none; font-family: Arial; display: inline-block; margin: 0; }
            h1 { font-size: 14px; margin: 0 0 7px 0; font-size: 14px; font-weight: bold; color: #555; }
            h2 { font-size: 12px; font-weight: normal; display: inline; margin-right: 8px; }
            .par { margin-bottom: 20px; padding: 8px 12px 12px; background-color: #f7f8f9; width: 615px; border-radius: 3px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); }
            input { border: solid 1px #d0d1d2; padding: 5px 7px; font-size: 14px; }
            textarea { font-size: 13px; border: solid 1px #d0d1d2; }
            .text { width: 600px; height: 36px; resize: none; padding: 5px 7px; line-height: 1.4em; }
            .smallText { width: 300px; height: 18px; resize: none; padding: 5px 7px; line-height: 1.4em; }
            .detailsText { width: 600px; height: 18px; resize: none; padding: 5px 7px; line-height: 1.4em; }
            .smallDetailsText { width: 493px; margin-left: 104px; height: 18px; resize: none; padding: 5px 7px; line-height: 1.4em; }
            .inputSmall { font-size: 12px; padding: 2px 3px; }
            a.detailsButton { margin-left: 10px; padding-right: 20px; color: #a0a1a2; font-size: 12px; text-decoration: none; background-image: url('/application/layout/images/details-arrow-right.png'); background-repeat: no-repeat; background-position: 40px 5px; visibility: hidden; }
            a.detailsButton.visible { background-image: url('/application/layout/images/details-arrow-bottom.png'); background-repeat: no-repeat; background-position: 40px 6px; }
            span.note { font-size: 11px; color: #a0a1a2; }
        </style>

        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div style="width: 900px; margin: 20px auto;">

            <div class="par">
                <h1>
                    Name
                </h1>
                <input type="text" name="name" value="<?= '' ?>" />
            </div>

            <? include_view('templates/edit/berthing'); ?>

        </div>

    </body>
</html>