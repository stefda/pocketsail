<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/controllers/APIBroker.js"></script>
        <script>

            $(function() {
                $('#saveButton').click(function() {
                    var data = $('.attr').serialize();
                    API
                });
            });

        </script>
    </head>

    <body>

        <div id="poiForm">
            <div>
                <div>
                    Name
                </div>
                <input class="attr" type="text" name="name" value="<?= $poi->name() ?>" />
            </div>

            <div>
                <div>
                    Description
                </div>
                <textarea class="attr" name="attrs[description]"><?= @$attrs->description ?></textarea>
            </div>
            
            <div>
                <div>
                    Approach & Pilotage
                </div>
                <textarea class="attr" name="attrs[approach][description]"><?= @$attrs->appraoch->description ?></textarea>
                <select class="attr" type="checkbox" name="attrs[approach][drying][value]">
                    <option value="na" <?= @$attrs->approach->drying->value === 'na' ? 'selected' : '' ?>>Don't know</option>
                    <option value="no" <?= @$attrs->approach->drying->value === 'no' ? 'selected' : '' ?>>No</option>
                    <option value="yes" <?= @$attrs->approach->drying->value === 'yes' ? 'selected' : '' ?>>Yes</option>
                </select>
                <textarea class="attr" name="attrs[approach][drying][details]"><?= @$attrs->approach->drying->details ?></textarea>
            </div>

            <div>
                <div>
                    References
                </div>
                <textarea class="attr" name="attrs[references]"><?= @$attrs->references ?></textarea>
            </div>
        </div>

        <div>
            <input type="button" id="saveButton" value="Save" />
        </div>

    </body>
</html>