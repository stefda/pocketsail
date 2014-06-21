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
        <script src="/application/js/jquery/utils.js"></script>
        
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />

        <style type="text/css">
            h1 { font-size: 18px; font-weight: bold; margin: 0; padding: 0; }
            h3 { font-size: 16px; font-weight: bold; margin: 0; padding: 0; display: inline; }
        </style>

        <script>

            var validator = new Validator();

            $(function() {
                $('.tpl-select').select();
            });

        </script>

    </head>
    <body>
        
        <h1>Berthing</h1>
        Takze berthing je tady.<br /><br /><h3>Nadpis</h3><br />Toto je nadpis. Dalsi pod tim.

        <?= tpl_edit('marina') ?>

    </body>
</html>
