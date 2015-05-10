<!DOCTYPE html>
<html>

    <head>
        <title>Menu</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            body, html { width: 100%; height: 100%; }
            body { margin: 0; padding: 0; }
            .input { border: solid 1px #d0d1d2; font-size: 16px; padding: 5px 7px; outline: none; width: 100%; box-sizing: border-box; margin: 0; }
            .button { border: solid 1px #e0e1e2; background-color: #f0f1f2; font-size: 14px; padding: 5px 7px; box-sizing: border-box; margin: 0; color: #555; font-weight: bold; width: 200px; }
        </style>
    </head>

    <body>

        <div id="header" style="width: 100%; height: 60px; background-color: #e9eaeb;">
            <img src="/application/images/logo.png" style="float: left; margin: 14px 0 0 16px;" />
        </div>

        <form action="/user/do_login" method="post">

            <div style="width: 200px; margin: 50px auto;">
                <div style="margin-bottom: 6px;">
                    <input class="input" type="text" name="user" placeholder="Login" />
                </div>
                <div style="margin-bottom: 6px;">
                    <input class="input" type="password" name="password" placeholder="Password" />
                </div>
                <div style="margin-bottom: 10px;">
                    <input class="button" type="submit" value="Sign in" />
                </div>
            </div>

        </form>

    </body>
</html>
