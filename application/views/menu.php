<!DOCTYPE html>
<html>
    <head>
        <title>Menu</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="/application/js/jquery/jquery.js"></script>
        <style>
            ul.menu {
                display: none;
                cursor: default;
                list-style: none;
                list-style-type: none;
                position: absolute;
                border: solid 1px #d0d1d2;
                padding: 4px 0;
                margin: 0;
                font-family: Arial;
                font-size: 12px;
                background-color: #fff;
                box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.3);
            }
            ul.menu li { color: #333; padding: 4px 15px; margin: 0 -1px; }
            ul.menu li:hover { background-color: #3079ed; color: #fff; }
            ul.menu li.stay { background-color: #3079ed; color: #fff; }
        </style>
        <script>
            $(function() {

                $('#menu').css('top', '200px');
                $('#menu').css('left', '200px');
                $('#menu').show();

                $('.menu .submenu').hide();

                var T = null;

                $('.menu li').mouseover(function() {
                    if (T !== null) {
                        clearTimeout(T);
                        T = null;
                    }
                    var openSubmenu = $('.menu .menu:visible');
                    openSubmenu.parent('li').removeClass('stay');
                    if (openSubmenu[0] !== $(this).find('.menu')[0]) {
                        var this_ = this;
                        T = setTimeout(function() {
                            openSubmenu.hide();
                        }, 300);
                    }
                    if ($(this).find('.menu').length) {
                        var this_ = this;
                        T = setTimeout(function() {
                            var submenu = $(this_).find('.menu');
                            var pos = $(this_).position();
                            var width = $(this_).width();
                            submenu.css('left', width + 27 + 'px');
                            submenu.css('top', pos.top - 5 + 'px');
                            submenu.show();
                            $(this_).addClass('stay');
                        }, 300);
                    }
                });
            });
        </script>
    </head>
    <body>

        <ul id="menu" class="menu">
            <li data-cat="geo">
                Geographic feature
                <ul class="menu">
                    <li data-type="sub" data-sub="cove">Cove</li>
                    <li data-type="sub" data-sub="bay">Bay</li>
                    <li data-type="sub" data-sub="island">Island</li>
                </ul>
            </li>
            <li data-type="cat" data-cat="admin">Admin feature</li>
            <li data-type="cat" data-cat="berthing">Berthing & Anchoring</li>
            <li data-type="cat" data-cat="shopping">
                Shopping
                <ul class="menu">
                    <li data-type="sub" data-sub="cove">Cove</li>
                    <li data-type="sub" data-sub="bay">Bay</li>
                    <li data-type="sub" data-sub="island">Island</li>
                </ul>
            </li>
            <li data-type="cat" data-cat="goingout">Going out</li>
        </ul>

    </body>
</html>
