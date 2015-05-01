<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/brokers/PhotoBroker.js"></script>
        <script>
            $(function () {

                var poiId = 4;
                var init = false;

                $('#preview').on('click', '.rotateButton', function () {
                    var id = $(this).data('id');
                    var photo = $(this);
                    PhotoBroker.rotate({
                        'post': {
                            'id': id
                        },
                        'success': function (res) {
                            photo.closest('.photo').find('img').attr('src', '/data/photos/gallery/' + id + '.jpg?' + new Date().getTime());
                        }
                    });
                });

                $('#trigger').click(function () {
                    $("#photo").click();
                });

                $('#photoUploadFrame').load(function () {

                    if (!init) {
                        console.log('iframe init');
                        init = true;
                        return;
                    }

                    var text = $(this).contents().find('body').text();
                    var res = $.parseJSON(text);

                    if (res.status === 'OK') {
                        
                        $('#photo').val('');
                        $('#preview').html('');
                        
                        var ids = res.ids;
                        var first = 0;
                        for (var i = 0; i < ids.length; i++) {
                            $('#preview').append(
                                    '<div class="photo">' +
                                    '<span class="photoSettings"></span>' +
                                    '<img src="/data/photos/gallery/' + ids[i] + '.jpg" />' +
                                    '<input type="hidden" name="id[]" value="' + ids[i] + '" />' +
                                    'Main <input type="radio" name="main" value="' + ids[i] + '" ' + (first++ === 0 ? 'checked ' : '') + '/><br />' +
                                    '<input type="text" name="description[]" /><br />' +
                                    '<input class="rotateButton" type="button" value="Rotate" data-id="' + ids[i] + '" />' +
                                    '<div>');
                        }
                    }
                });
            });
        </script>
        <style>
            .photo { position: relative; float: left; margin: 5px; }
            .photo img { display: block; }
            .photo .photoSettings { display: block; position: absolute; top: 190px; left: 190px; width: 29px; height: 29px; background-image: url('/application/images/settings-button.png'); }
            .photo .photoSettings:hover { cursor: pointer; cursor: hand; }
        </style>
    </head>
    <body>
        <form id="photoForm" name="photoForm" method="post" enctype="multipart/form-data" action="/photo/upload" target="photoUploadFrame">
            <input type="hidden" name="poiId" value="4" /><br />
            <input type="button" value="FILE UPLOAD!" id="trigger" />
            <div style="height: 0px; overflow: hidden;">
                <input id="photo" type="file" name="photo[]" multiple /><br />
            </div>
            <input type="submit" value="Upload" />
        </form>
        <iframe name="photoUploadFrame" id="photoUploadFrame" src="/photo/index"></iframe>
        <form id="previewForm">
            <div id="preview">
            </div>
            <input type="submit" value="Confirm photos" />
        </form>
    </body>
</html>
