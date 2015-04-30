<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="/application/js/jquery/jquery.js"></script>
        <script>
            $(function () {

                var files;

                $('#file').on('change', function (e) {
                    files = e.target.files;
                });

                $('#form').on('submit', function (e) {

                    e.stopPropagation();
                    e.preventDefault();

                    var data = new FormData();

                    $.each(files, function (key, value) {
                        data.append(key, value);
                    });

                    $.ajax({
                        url: '/test/upload',
                        type: 'POST',
                        data: data,
                        cache: false,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data, textStatus, jqXHR) {
                            if (typeof data.error === 'undefined') {
                                // Success so call function to process the form
                                submitForm(event, data);
                            } else {
                                // Handle errors here
                                console.log('ERRORS: ' + data.error);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            // Handle errors here
                            console.log('ERRORS: ' + textStatus);
                            // STOP LOADING SPINNER
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        <form id="form" action="/upload" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file" />
            <input type="submit" />
        </form>
    </body>
</html>
