<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>

        <script src="/application/js/brokers/APIBroker.js"></script>

        <script src="/application/js/geo/Geo.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LineString.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Bounds.js"></script>
        <script src="/application/js/geo/ViewBounds.js"></script>
        <script src="/application/js/Map.js"></script>
        <script src="/application/js/MapStyle.js"></script>

        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>
        <script src="/application/js/geo/Projector.js"></script>
        <script src="/application/js/geo/Position.js"></script>

        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/brokers/TestBroker.js"></script>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>

        <script>

            function n(name, type) {
                name = name.replace(/\]/g, '\\]');
                name = name.replace(/\[/g, '\\[');
                var selector = '[name=' + name + ']' + (type === undefined ? '' : (':' + type));
                return $(selector);
            }

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
            var id = <?= $poi->id() ?>;
            var cat = '<?= $poi->cat() ?>';
            var sub = '<?= $poi->sub() ?>';
            var latLng = LatLng.fromWKT('<?= $poi->latLng() === null ? 'NULL' : $poi->latLng()->toWKT() ?>');
            var border = Polygon.fromWKT('<?= $poi->border() === null ? 'NULL' : $poi->border()->toWKT() ?>');

            $(function() {

                var map = new Map('canvas', new LatLng(43.866433, 15.309780), 12);

                $('.tpl-select').select();
                $('.tpl-select-button').selectButton();

                // Inputs outline
                $('a,input,textarea').focus(function() {
                    $(this).addClass('ps-ui-focus');
                });

                $('a,input,textarea').blur(function() {
                    $(this).removeClass('ps-ui-focus');
                });

                $('textarea').autosize({
                    append: false
                });

                $('.tpl-delete-button').click(function() {
                    $(this).closest('tr').remove();
                });

                // Hacky, but works
                $('.tpl-details-button').click(function(e) {
                    var elem = $(this).closest('.tpl-has-details-button');
                    if ($(this).hasClass('tpl-stay-visible')) {
                        $(this).removeClass('tpl-stay-visible');
                        elem.next('.tpl-details').hide();
                    } else {
                        $(this).addClass('tpl-stay-visible');
                        var details = elem.next('.tpl-details').show();
                        details.find('textarea').autosize().show().trigger('autosize.resize');
                    }
                    e.preventDefault();
                });

                $('.tpl-details textarea').keyup(function() {
                    if ($(this).val() === '') {
                        $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').removeClass('tpl-visible');
                        $(this).removeClass('attr-include');
                    } else {
                        $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').addClass('tpl-visible');
                        $(this).addClass('attr-include');
                    }
                });

                $('#saveButton').click(function() {
                    if (validator.validate()) {
                        var name = $('[name=name]').val();
                        var attrs = $('.attr:visible,.attr-include').serialize()
                        TestBroker.post({
                            post: $.param({
                                id: id,
                                name: name,
                                cat: cat,
                                sub: sub,
                                latLng: latLng.toWKT(),
                                border: border === null ? null : border.toWKT()
                            }) + '&' + attrs,
                            success: function(res) {
                                //console.log(res);
                                location.reload();
                            }
                        });
                    } else {
                        alert('Error');
                    }
                });
            });

        </script>

        <style>

            html, body { font-family: Arial; background-color: #fff; }
            body { overflow-y: scroll; }
            a, input, textarea, select { outline: none; font-family: Arial; display: inline-block; margin: 0; }
            h1 { font-size: 14px; margin: 0 0 7px 0; font-weight: bold; color: #555; }
            h2 { font-size: 12px; margin: 0 2px 7px 0; font-weight: normal; color: #333; display: inline; }
            input { font-size: 13px; border: solid 1px #d0d1d2; padding: 5px 7px; }
            textarea { display: block; box-sizing: border-box; font-size: 13px; border: solid 1px #d0d1d2; padding: 5px 7px; line-height: 1.4em; }

            .tpl-section { width: 600px; margin-bottom: 20px; background-color: #f7f8f9; border-radius: 3px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); }
            .tpl-section-wrapper { padding: 10px 12px; }
            .tpl-subsection { margin-top: 10px; padding-top: 10px; border-top: solid 1px #e0e1e2; padding-bottom: 5px; }

            .tpl-details-large { width: 100%; height: 48px; resize: none; }
            .tpl-details-small { width: 100%; height: 24px; resize: none; }

            .tpl-table { border-collapse: collapse; }
            .tpl-table td { padding-bottom: 3px; }
            .tpl-table-item-label { text-align: right; width: 70px; font-size: 12px; }
            .tpl-table-item-value { padding-left: 5px; }

            .tpl-text-small { font-size: 12px; padding: 2px 3px; }
            .tpl-note { font-size: 11px; color: #a0a1a2; }

            .tpl-details-button { display: none; cursor: pointer; margin-left: 10px; padding-right: 20px; color: #a0a1a2; font-size: 12px; text-decoration: none; background-image: url('/application/layout/images/details-arrows.png'); background-repeat: no-repeat; background-position: 40px -10px; }
            .tpl-details-button.tpl-stay-visible { display: inline; background-repeat: no-repeat; background-position-y: 6px; }
            .tpl-details-button.tpl-visible { display: inline; color: #3079ed; background-position-y: -40px; }
            .tpl-details-button.tpl-stay-visible.tpl-visible { background-position-y: -24px; }
            .tpl-has-details-button:hover .tpl-details-button { display: inline; }
            .tpl-details { display: none; margin-top: 8px; }
            .tpl-details td { padding: 4px 0 8px; }

            .tpl-delete-button { cursor: pointer; display: block; width: 10px; height: 10px; background-repeat: no-repeat; background-image: url('/application/layout/images/delete-cross.png'); }
            .tpl-delete-button:hover { background-position-y: -10px; }


        </style>

        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />

    </head>
    <body>

        <div style="position: fixed; top: 20px; left: 960px;">
            <input type="button" id="saveButton" value="Save" />
        </div>

        <div style="width: 900px; margin: 20px auto;">

            <div id="canvas" style="width: 100%; height: 200px; margin-bottom: 20px;"></div>

            <div class="tpl-section">
                <div class="tpl-section-wrapper">

                    <h1>Name</h1>
                    <input type="text" name="name" value="<?= $poi->name() ?>" style="width: 300px;" />

                </div>
            </div>

            <? include_view('templates/edit/berthing'); ?>

        </div>

    </body>
</html>