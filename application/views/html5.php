<!DOCTYPE html>
<html>
    <head>
        <title>Custom tags test</title>
        <meta charset="utf-8">
        <script type="text/javascript" src="/application/js/jquery/jquery.js"></script>
        <script type="text/javascript" src="/application/js/jquery/jquery-serialize.js"></script>
        <script type="text/javascript">
            
            $(function() {

                $('#go').click(function () {
                    var doc = $('.poi').serialize();
                    //console.log(doc);
                    $('#out').html(JSON.stringify(doc));
                });
                
                $('#add').click(function() {
                    var template = $('[data-class="maxDraught[]"]').last();
                    var clone = template.clone();
                    clone.find('input, select, textarea').val('');
                    clone.insertAfter(template);
                });
            });
        </script>
    </head>
    <body>

        <div class="poi">
            <div data-class="description">
                <textarea data-attribute="text">Description jak má být.</textarea>
                <br />
                <textarea data-attribute="details">Detaily, které nikdo nečte.</textarea>
            </div>
            <div data-class="berthing">
                <div>
                    <div data-class="maxDraught[]">
                        <select data-attribute="unit">
                            <option value="meters">Meters</option>
                            <option value="feet">Feet</option>
                        </select>
                        <input data-attribute="value" type="number" step="any" value="34" />
                        <textarea data-attribute="text">Dalsi</textarea>
                    </div>
                    <input data-attribute="maxLength" type="text" value="15" />
                    <textarea data-attribute="details">Details.</textarea>
                </div>
            </div>
        </div>

        <input type="button" value="GO" id="go" /><br />
        <input type="button" value="Add" id="add" /><br />

        <div id="out"></div>

    </body>
</html>

