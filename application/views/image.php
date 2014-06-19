<!DOCTYPE html>
<html>
    
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>-->
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/jquery-plugins.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>

        <style type="text/css">
            
        </style>

        <script>

            $(function() {
            });

        </script>
        
    </head>
    <body>

        <form method="post" enctype="multipart/form-data" action="/test/save_image">
            <input type="file" name="img" /><br />
            <input type="text" name="credits" placeholder="Credits" /><br />
            <input type="text" name="description" placeholder="Description" /><br />
            <input type="submit" value="Save image" />
        </form>
        
        <img src="<?= $path ?>" />
        
    </body>
</html>
