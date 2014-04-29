<html>
    <head>

        <style>
            body { font-family: Arial; color: #333; font-size: 13px; }
            input::-ms-clear { display: none; }
            textarea { outline: none; resize: none; line-height: 1.2em; }
            .area { font-family: Arial; font-size: 14px; color: #333; line-height: 1.2em; }
            .style { border: solid 1px #d0d1d2; box-shadow: 0 1px 1px #eee inset; outline: none; }
            #edit { display: block; margin: 0; border: solid 1px #d0d1d2; width: 200px; padding: 3px; height: 0; -moz-padding-start: 2px; -moz-padding-end: 2px; }
            #select { cursor: pointer; float: left; padding: 1px 14px 1px 3px; border: solid 1px #d0d1d2; border-radius: 3px; color: #b0b1b2; font-size: 12px; }
            ::-webkit-input-placeholder { color: #c0c1c2; }
            :-moz-placeholder { color: #c0c1c2; }
            ::-moz-placeholder { color: #c0c1c2; }
            :-ms-input-placeholder { color: #c0c1c2; }
            ul { list-style: none; list-style-type: none; margin: 0; padding: 0; border: solid 1px #c0c1c2; border-radius: 3px; box-shadow: 0 0 5px #ccc; padding: 10px; font-size: 12px; background-color: #fff; }
            .item { cursor: pointer; }
            .item a:hover { background-color: #aaa; }
            .item a.on { color: #333; }
        </style>
        <!--[if IE]>
        <style>
            #edit { padding: 3px 3px 2px 3px; }
        </style>
        <![endif]-->

    </head>

    <body>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/utils.js"></script>
        <script src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="/application/js/jquery/jquery-autogrow.js"></script>
        <script>
            $(function() {
                $('#grow').keypress(function(e) {
                    var c = String.fromCharCode(e.charCode);
                    var text = $(this).val();
                    $('#test').html(text + c);
                    var width = $(this).width();
                    var testWidth = $('#test').width();
                    if (width < testWidth + 3) {
                        $(this).width(testWidth + 3);
                    }
                });

                $('#edit').autosize();

                var edit = false;

                $('#change').click(function() {
                    if (edit) {
                        var text = $('#edit').val();
                        $('#view').html(nl2br(text));
                        $('#edit').hide();
                        $('#view').show();
                        edit = false;
                    }
                    else {
                        var text = $('#view').text();
                        $('#edit').val(text);
                        $('#view').hide();
                        $('#edit').css('display', 'block');
                        edit = true;
                    }
                });

                function nl2br(str, is_xhtml) {
                    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
                }

                $('#select').hover(function() {
                    $(this).css('box-shadow', '0 0 1px #bbb');
                }, function() {
                    $(this).css('box-shadow', 'none');
                });

                $('#grow').hover(function() {
                    $(this).css('border-color', '#b0b1b2');
                }, function() {
                    $(this).css('border-color', '#d0d1d2');
                });

                $('#select').click(function() {
                    var select = $('#selectSource');
                    //$('#selectSource').val('sternto');
                    //alert($('#selectSource').val());
                    getmenu(select, $('#selectSource').val(), $(this).position());
                });

                function getmenu(select, selected, position) {
                    var list = $('<ul></ul>');
                    list.addClass('menu');
                    var i = 0;
                    var index = 0;
                    select.find('option').each(function() {
                        var item = $('<li></li>');
                        item.html('<a>' + $(this).html() + '</a>');
                        item.attr('val', $(this).val());
                        item.css('height', 15);
                        item.addClass('item');
                        list.append(item);
                        if ($(this).val() === selected) {
                            index = i;
                        }
                        i++;
                    });
                    list.css('position', 'absolute');
                    list.css('top', position.top - index * 15 - 9);
                    list.css('left', position.left - 7);
                    $('body').append(list);
                }

//                $('html').click(function() {
//                    $('.menu:visible').hide();
//                });
                
                $('.select').click(function(e) {
                    e.stopPropagation();
                });
                
                $('.menu').live('click', function(e) {
                    e.stopPropagation();
                });
                
                $('.item').live('click', function(e) {
                    e.stopPropagation();
                    var value = $(this).attr('val');
                    var label = $(this).text();
                    $('#selectSource').val(value);
                    $('#select .label').text(label);
                    $('.menu:visible').hide();
                    $('#selectSource').focus();
                });
                
                $('#selectSource').focus(function() {
                    $('#select').css('border-color', '#c0c1c2');
                });
                
                $('#selectSource').blur(function() {
                    $('#select').css('border-color', '#d0d1d2');
                });
                
                $('#selectSource').keydown(function(e) {
                    if (e.which === 32) {
                        $('#select').trigger('click');
                    }
                });
            });
        </script>

        <div>
        </div>

        <div id="test" style="position: absolute; top: 500px; left: 500px; border: solid 1px #222; margin: 0; padding: 1px 2px; font-size: 12px; font-family: Arial;"></div>

        <div id="wrapper">
            <div class="area" id="view" style="display: block; border: solid 1px #fff; width: 192px; padding: 3px; word-wrap: break-word;">Jak se mate?</div>
            <textarea class="area style" id="edit" style="display: none;">Jak se mate?</textarea>
        </div>

        <input type="button" id="change" value="change" />

        <div style="margin-top: 100px;">

            <div style="float: left; color: #333; font-weight: bold; font-size: 12px; margin-right: 20px; padding-top: 2px;">Sea berths</div>

            <input type="text" class="style" id="grow" style="display: block; margin: 0; padding: 1px 2px; font-size: 12px; font-family: Arial; float: left; width: 52px" placeholder="Number" />

            <div style="float: left; margin-left: 6px;">
                <div class="select" id="select" style="position: relative;">
                    <div style="position: absolute; right: 5px; top: 5px; width: 5px; height: 8px; background-image: url('/application/images/select-arrows.png')"></div>
                    <span class="label">Stern-to</span>
                </div>
                <select id="selectSource" style="width: 0; height: 0; position: absolute; top: -9999; left: -9999;">
                    <option value="">Choose type</option>
                    <option value="sternto" selected="true">Stern-to</option>
                    <option value="t-alongside">T/Alongside</option>
                </select>
            </div>

        </div>

        <div style="padding-top: 5px; clear: both;">

            <div style="float: left; color: #333; font-weight: bold; font-size: 12px; margin-right: 11px; padding-top: 2px;">Max draught</div>

            <input type="text" class="style" id="grow" style="margin: 0; padding: 1px 2px; font-size: 12px; font-family: Arial; display: block; float: left; width: 44px" placeholder="Height" />

        </div>

    </body>
</html>