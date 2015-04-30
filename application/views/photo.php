<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script>
            $(function () {

                var init = false;

                $('#photoUploadFrame').load(function () {
                    
                    if (!init) {
                        init = true;
                        return;
                    }
                    
                    var text = $(this).contents().find('body').text();
                    var res = $.parseJSON(text);
                    
                    if (res.status === 'OK') {
                        $('#photo').val('');
                        $('#title').val('');
                        $('#description').val('');
                    }
                });
            });
        </script>
    </head>
    <body>
        <form id="photoForm" name="photoForm" method="post" enctype="multipart/form-data" action="/photo/upload" target="photoUploadFrame">
            <input type="hidden" name="poiId" value="4" /><br />
            <input id="photo" type="file" name="photo" /><br />
            <input id="title" type="text" name="title" /><br />
            <input id="description" type="text" name="description" /><br />
            <input type="submit" value="Upload" />
        </form>
        <iframe name="photoUploadFrame" id="photoUploadFrame" src="/photo/index">
        </iframe>
</body>
</html>
