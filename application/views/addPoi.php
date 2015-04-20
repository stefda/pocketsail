<!DOCTYPE html>
<html>
    <head>
        <script src="/application/js/geo/Point.js"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-serialize.js"></script>
        <script>
            $(function () {

                var latLng = Point.fromGeoJson({
                    'type': "Point",
                    'coordinates': [16.4, 43.2]
                });

                $('form').submit(function () {
                    var doc = $('body').serialize();
                    doc.latLng = latLng.toGeoJson();
                    $(this).append(
                            $('input')
                            .attr('type', 'hidden')
                            .attr('name', 'data')
                            .val(JSON.stringify(doc))
                            );
                });
            });
        </script>
    </head>
    <body>
        <div>
            Name <input data-attribute="name" type="text" />
        </div>
        <div>
            URL <input data-attribute="url" type="text" />
        </div>
        <div>
            Description <textarea data-attribute="description"></textarea>
        </div>
        <div data-class="berthing">
            <div data-class="assistance">
                <select data-attribute="value">
                    <option value="na">?</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <textarea data-attribute="details"></textarea>
            </div>
            <div>
                <select data-attribute="type" multiple>
                    <option value="alongside">Alongside</option>
                    <option value="stern-to">Stern-to</option>
                    <option value="mooring-lines">Mooring Lines</option>
                </select>
            </div>
            <div>
                Depth <input data-attribute="maxDepth" type="text" />
            </div>
        </div>
        <div>
            <form method="post" action="/test/add">
                <input id="saveButton" type="submit" value="Save" />
            </form>
        </div>
    </body>
</html>