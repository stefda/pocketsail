<!DOCTYPE html>
<html>
    <head>
        <title>Marina Template</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/utils.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/scroll.pane/jquery.mousewheel.js"></script>
        <script src="/application/js/jquery/scroll.pane/scroll.pane.js"></script>
        <link href="/application/js/jquery/scroll.pane/scroll.pane.css" rel="stylesheet" type="text/css" />
        <style>
            body { margin: 0; padding: 0; font-family: Arial; font-size: 14px; color: #333; }
            h1 { margin: 0 0 0 0; font-size: 24px; font-weight: normal; }
            h2 { margin: 15px 0 5px 0; font-size: 18px; font-weight: normal; }
            h3 { margin: 0 0 0 0; font-size: 16px; font-weight: normal; }
            a { outline: none; -moz-outline-style: none; }
            div.wrapper { position: relative; margin: 40px auto; width: 850px; height: 500px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.4); border-radius: 3px; }
            div.innerWrapper { position: absolute; left: 20px; right: 0; bottom: 20px; top: 36px; }
            div.innerLeft { padding-top: 20px; width: 520px; }
            div.innerRight { margin-top: 20px; float: right; width: 250px; border: solid 1px #e0e1e2; }
            table.list { margin: 10px 0 5px 0; font-size: 13px; border-collapse: collapse; }
            table.list td { text-align: right; }
            .button-ynn { position: relative; width: 77px; height: 15px; color: #333; font-size: 10px; font-weight: bold; box-shadow: 0 1px 1px #eee; }
            .button-ynn a { line-height: 13px; height: 13px; position: absolute; text-decoration: none; background-color: #f4f5f6; border: solid 1px #d9d9d9; text-align: center; color: #333; }
            .button-ynn a:hover { z-index: 9998; border-color: #c0c1c2; background-color: #f0f1f2; }
            .button-ynn a:active { color: #333; }
            .button-ynn a.hover { z-index: 9998; border-color: #c0c1c2; background-color: #f0f1f2; }
            .button-ynn a.nk { left: 0; width: 22px; border-radius: 2px 0 0 2px; }
            .button-ynn a.yes { left: 23px; width: 27px; }
            .button-ynn a.no { left: 51px; width: 24px; border-radius: 0 2px 2px 0; }
            .button-ynn a.selected { z-index: 9999; background-color: #4c8efc; border: solid 1px #3079ed; color: #fff; }
            a.select { vertical-align: bottom; line-height: 17px; height: 17px; display: inline-block; font-size: 12px; text-decoration: none; color: #333; padding: 0 17px 0 4px; cursor: pointer; box-shadow: 0 1px 1px #eee; background-color: #f4f5f6; border: solid 1px #d9d9d9; border-radius: 2px; background-image: url('/application/images/select-arrows.png'); background-repeat: no-repeat; background-position: top 3px right 4px; }
            a.select:hover { border-color: #c0c1c2; background-color: #f0f1f2; }
            a.select:active { color: #333; }
            a.select:visited { color: #333; }            
            a.select.hover { border-color: #c0c1c2; background-color: #f0f1f2; }
            ul.selectList { position: absolute; left: 300px; list-style: none; list-style-type: none; padding: 3px 0; margin: 0; background-color: #fff; border: solid 1px #e0e1e2; border-radius: 2px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); }
            .selectList a { text-align: left; text-decoration: none; color: #333; font-size: 12px; cursor: pointer; display: list-item; padding: 3px 23px 3px 13px; }
            .selectList a.hover { background-color: #f0f1f2; }
            .selectList a.selected { background-color: #f0f1f2; }
            table.contact { font-size: 13px; }
            table.contact td { padding-bottom: 4px; }
            table.contact input { margin: 0; display: block; outline: none; font-size: 13px; border: solid 1px #c0c1c2; padding-left: 3px; }
            input.active { border-color: #999 !important; box-shadow: 0 0 3px rgba(0, 0, 0, 0.15) inset; }
            a.details { color: #4c8efc; text-decoration: none; display: inline-block; padding-right: 8px; background-image: url('/application/images/details-arrow.png'); background-repeat: no-repeat; background-position: right 0 top 6px; }
            a.details:hover { color: #3079ed; background-image: url('/application/images/details-arrow-hover.png'); }

            textarea { outline: none; resize: none; line-height: 1.2em; }
            .area { font-family: Arial; font-size: 14px; color: #333; line-height: 1.2em; }
            .style { border: solid 1px #d0d1d2; box-shadow: 0 1px 1px #eee inset; outline: none; }
            #edit { display: block; margin: 0; border: solid 1px #d0d1d2; width: 200px; padding: 3px; height: 0; -moz-padding-start: 2px; -moz-padding-end: 2px; }
            
            div { outline: none; }

        </style>
        <script>
            $(function() {
                $('input.yesno').ynk();
                $('select').select();
                $('input').focus(function() {
                    $(this).addClass('active');
                });
                $('input').blur(function() {
                    $(this).removeClass('active');
                });
                $('#edit').autosize();
                $('#canvas').jScrollPane({
                    mouseWheelSpeed: 30
                });
//                $('.jspTrack').hover(function() {
//                    $(this).css('background-color', '#f0f1f2');
//                }, function() {
//                    $(this).css('background-color', '#fff');
//                });
            });
        </script>
    </head>
    <body>

        <div class="wrapper">
            <div style="height: 35px; width: 100%; background-color: #f0f1f2; border-bottom: solid 1px #e0e1e2; border-radius: 2px 0 0 2px;">
                
            </div>
            <div class="innerWrapper" id="canvas">
                <div class="innerRight">
                    <div style="padding: 10px;">
                        <h3>Contact</h3>
                        <table class="list contact" style="margin-top: 10px;">
                            <tr>
                                <td style="text-align: right; padding-right: 7px; width: 54px;"><select name="info[contact][type][]"><option value="www">www</option><option value="email" selected="true">email</option><option value="tel">tel</option><option value="fax">fax</option><option value="vhf">vhf</option></select></td>
                                <td><input type="text" name="info[contact][value][]" value="zadar@gmail.com" /></td>
                            </tr>
                            <tr>
                                <td style="text-align: right; padding-right: 7px; width: 54px;"><select name="info[contact][type][]"><option value="www" selected="true">www</option><option value="email">email</option><option value="tel">tel</option><option value="fax">fax</option><option value="vhf">vhf</option></select></td>
                                <td><input type="text" name="info[contact][value][]" value="http://www.zadar.com" /></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="innerLeft">

                    <div class="para">
                        <h1>Marina Zadar</h1>
                        <div style="font-size: 14px;">44°17'.032N 017°13'.223E</div>
                    </div>

                    <div id="wrapper">
                        <div class="area" id="view" style="display: block; border: solid 1px #fff; width: 192px; padding: 3px; word-wrap: break-word;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna</div>
                        <textarea class="area style" id="edit" style="display: none;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna</textarea>
                    </div>

                    <div style="margin-top: 15px;">
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit.
                    </div>
                    <h2>Navigation</h2>
                    <div>
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam.
                    </div>
                    <h2>Hazards</h2>
                    <div>
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                    </div>
                    <h2>Going out</h2>
                    <div>
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam.
                    </div>
                    <table class="list">
                        <tr style="height: 23px;">
                            <td style="text-align: right; padding-right: 10px;">Restaurants</td>
                            <td><input class="yesno" name="info[going_out][restaurant][state]" type="text" value="yes" /></td>
                            <td style="padding-left: 10px;"><a href="" class="details">details</a></td>
                        </tr>
                        <tr style="height: 23px;">
                            <td style="text-align: right; padding-right: 10px;">Bars</td>
                            <td><input class="yesno" name="info[going_out][bar][state]" type="text" value="yes" /></td>
                            <td style="padding-left: 10px;"><a href="" class="details">details</a></td>
                        </tr>
                        <tr style="height: 23px;">
                            <td style="text-align: right; padding-right: 10px;">Cafés</td>
                            <td><input class="yesno" name="info[going_out][cafe][state]" type="text" value="nk" /></td>
                        </tr>
                        <tr style="height: 23px;">
                            <td style="text-align: right; padding-right: 10px;">Clubs</td>
                            <td><input class="yesno" name="info[going_out][club][state]" type="text" value="no" /></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </body>
</html>