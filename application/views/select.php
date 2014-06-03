<!DOCTYPE html>
<html>
    <head>
        <title>Templates testing</title>
        <meta charset="UTF-8" />
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/jquery/utils.js"></script>
        <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>-->
        <script>
            $(function() {

                $('select').select();
                $('input.yesno').ynk();
                //$('select').select();

                $('input').focus(function() {
                    $(this).addClass('active');
                });

                $('input').blur(function() {
                    $(this).removeClass('active');
                });
            });
        </script>
        <style>

            body { font-size: 13px; font-family: Arial; color: #333; padding-left: 200px; }
            h1 { font-size: 20px; }
            table { border-collapse: collapse; }

            a { outline: none; -moz-outline-style: none; }


            .button-ynn { position: relative; width: 77px; height: 15px; color: #333; font-size: 10px; font-weight: bold; box-shadow: 0 1px 1px #eee; }
            .button-ynn a { line-height: 13px; height: 13px; position: absolute; text-decoration: none; background-color: #f4f5f6; border: solid 1px #d9d9d9; text-align: center; color: #333; }
            .button-ynn a:hover { z-index: 9998; border-color: #c0c1c2; background-color: #f0f1f2; }
            .button-ynn a:active { color: #333; }
            .button-ynn a.hover { z-index: 9998; border-color: #c0c1c2; background-color: #f0f1f2; }
            .button-ynn a.nk { left: 0; width: 22px; border-radius: 2px 0 0 2px; }
            .button-ynn a.yes { left: 23px; width: 27px; }
            .button-ynn a.no { left: 51px; width: 24px; border-radius: 0 2px 2px 0; }
            .button-ynn a.selected { z-index: 9999; background-color: #4c8efc; border: solid 1px #3079ed; color: #fff; }

            a.edit { text-decoration: none; color: #3079ed; padding-top: 3px; }
            a.edit:hover { text-decoration: underline; }

            a.select { vertical-align: bottom; line-height: 17px; height: 17px; display: inline-block; font-size: 12px; text-decoration: none; color: #333; padding: 0 17px 0 4px; cursor: pointer; box-shadow: 0 1px 1px #eee; background-color: #f4f5f6; border: solid 1px #d9d9d9; border-radius: 2px; background-image: url('/application/images/select-arrows.png'); background-repeat: no-repeat; background-position: top 3px right 4px; }
            a.select:hover { border-color: #c0c1c2; background-color: #f0f1f2; }
            a.select:active { color: #333; }
            a.select:visited { color: #333; }            
            a.select.hover { border-color: #c0c1c2; background-color: #f0f1f2; }
            ul.selectList { position: absolute; left: 300px; list-style: none; list-style-type: none; padding: 3px 0; margin: 0; background-color: #fff; border: solid 1px #e0e1e2; border-radius: 2px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); }
            .selectList a { text-decoration: none; color: #333; font-size: 12px; cursor: pointer; display: list-item; padding: 3px 23px 3px 13px; }
            .selectList a.hover { background-color: #f0f1f2; }
            .selectList a.selected { background-color: #f0f1f2; }

            .contact input { margin: 0; display: block; outline: none; font-size: 13px; border: solid 1px #c0c1c2; padding-left: 3px; }
            input.active { border-color: #999; box-shadow: 0 0 3px rgba(0, 0, 0, 0.1) inset; }

            .contact td { vertical-align: top; }
            table.contact tr { height: 25px; }

        </style>
    </head>
    <body>

        <div style="width: 500px;">
            <h1>Going out</h1>
        </div>

        <form method="POST" action="post">
            <input type="text" name="first" />
            <table style="font-size: 13px;">
                <tr style="height: 23px;">
                    <td style="text-align: right; padding-right: 10px;">
                        Restaurants
                    </td>
                    <td>
                        <input class="yesno" name="restaurants" type="text" value="yes" />
                    </td>
                </tr>
                <tr style="height: 23px;">
                    <td style="text-align: right; padding-right: 10px;">
                        Cafés
                    </td>
                    <td>
                        <input class="yesno" name="cafes" type="text" value="yes" />
                    </td>
                </tr>
                <tr style="height: 23px;">
                    <td style="text-align: right; padding-right: 10px;">
                        Bars
                    </td>
                    <td>
                        <input class="yesno" name="bars" type="text" value="nk" />
                    </td>
                </tr>
                <tr style="height: 23px;">
                    <td style="text-align: right; padding-right: 10px;">
                        Clubs
                    </td>
                    <td>
                        <input class="yesno" name="clubs" type="text" value="no" />
                    </td>
                </tr>
            </table>

            <div>
                <select name="info[contact][type][]">
                    <option value="0">January</option>
                    <option value="1" selected="true">February</option>
                    <option value="2">March</option>
                    <option value="3">April</option>
                    <option value="4">May</option>
                </select>
            </div>

            <table style="font-size: 13px; margin: 20px 0;" class="contact">
                <tr>
                    <td style="text-align: right; padding-right: 7px; width: 54px;"> 
                        <select name="info[contact][type][]">
                            <option value="0">January</option>
                            <option value="1" selected="true">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="info[contact][value][]" value="zadar@gmail.com" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-right: 7px;"> 
                        <select name="info[contact][type][]">
                            <option value="www" selected="true">www</option>
                            <option value="email">email</option>
                            <option value="tel">tel</option>
                            <option value="fax">fax</option>
                            <option value="vhf">vhf</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="info[contact][value][]" value="http://www.zadar.hr" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-right: 7px;"> 
                        <select name="info[contact][type][]">
                            <option value="www">www</option>
                            <option value="email">email</option>
                            <option value="tel">tel</option>
                            <option value="fax">fax</option>
                            <option value="vhf" selected="true">vhf</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="info[contact][value][]" value="16, 17, 46"/>
                    </td>
                </tr>
            </table>

            <input type="submit" />
        </form>


        <div>
            <div style="border: solid 1px red; display: inline-block; padding: 0;">
                <a class="" style="border: solid 1px red; display: inline;" href="">Don't know</a>
            </div>
            <span>
                <select name="info[contact][type][]">
                    <option value="www">www</option>
                    <option value="email">email</option>
                    <option value="tel">tel</option>
                    <option value="fax">fax</option>
                    <option value="vhf" selected="true">vhf</option>
                </select>
            </span>
        </div>


    </body>
</html>