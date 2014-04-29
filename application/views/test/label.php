<html>
    <head>
        <script src="/application/js/jquery/jquery.js"></script>
        <script>
            $(function() {
                $('#text').click(function() {
                    alert('asd');
                });
            });
        </script>
    </head>
    <body>
        <style>
            .label {
                position: absolute;
                top: 200px;
                left: 400px;
                width: 1px;
                height: 1px;
                margin: 0 auto; 
                overflow: visible;
                background-color: red;
            }
            .label .icon {
                width: 20px;
                height: 20px;
                position: absolute;
                top: -10px;
                left: -10px;
                background-color: lightgrey;
                cursor: pointer;
            }
            .label .text {
                font-family: Arial;
                font-size: 13px;
                line-height: 13px;
                opacity: 0.5;
                position: relative;
                background-color: red;
                left: -50%;
                white-space: nowrap;
                cursor: pointer;
            }
            .textWrapper {
                display: inline-block;
                position: absolute;
                background-color: red;
            }
        </style>

        <div class="label">
            <div class="icon"></div>
            <div class="textWrapper">
                <div class="text">Palmizana je nadivana husa</div>
            </div>
        </div>

    </body>
</div>
</html>