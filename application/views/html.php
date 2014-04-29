<html>
    <head>
        <title>Test html</title>
        <style>

            body { font-family: Arial; font-size: 13px; color: #222; }
            h1 { font-size: 15px; margin: 0 0 10px; }

            .list-item { height: 25px; cursor: pointer; cursor: hand; clear: both; }
            .list-item .checkbox { float: left; margin-right: 3px; }
            .list-item .label { float: left; margin-right: 15px; color: #a1a1a1; }
            .list-item .attributes { float: left; display: none; }
            .list-item .attribute { float: left; }

            .list-item .attribute .view { display: none; }
            .list-item .attribute .view.show { display: block; }
            .list-item .attribute .view .placeholder { float: left; font-style: italic; display: none; }
            .list-item .attribute .view .placeholder.show { display: block; }

            .list-item .attribute .view .value { float: left; display: none; }
            .list-item .attribute .view .value.show { display: block; }

            .list-item .attribute .edit { float: left; margin-right: 4px; display: none; }
            .list-item .attribute .edit.show { display: block; }
            .list-item .attribute .edit .hint { float: left; margin-right: 4px; }
            .list-item .attribute .edit .input { float: left; }
            .list-item .attribute .edit .input input { border: solid 1px #909192; margin: 0; padding: 0 2px; outline-width: 0; }

            .list-item.selected .label { color: #222; }
            .list-item.selected .attributes { display: block; }

        </style>
        <script src="/application/js/jquery/jquery.js"></script>
        <script>
            $(function() {

                var focused = false;
                var I = null;

                function toggle_item(item) {
                    if (item.hasClass('selected')) {
                        item.find('.checkbox input').prop('checked', false);
                        item.removeClass('selected');
                    } else {
                        item.find('.checkbox input').prop('checked', true);
                        item.addClass('selected');
                    }
                }

                function collapse_item(item) {
                    item.find('.attribute').each(function() {
                        var attribute = $(this);
                        var value = attribute.find('input').val();
                        attribute.find('.view .value').html(value);
                    });
                    item.find('.attribute').each(function() {
                        var attribute = $(this);
                        var view = attribute.find('.view');
                        if (view.find('.value').html() !== '') {
                            view.find('.value').addClass('show');
                            view.find('.placeholder').removeClass('show');
                        } else {
                            view.find('.placeholder').addClass('show');
                            view.find('.value').removeClass('show');
                        }
                    });
                    item.find('.attribute').each(function() {
                        var attribute = $(this);
                        attribute.find('.edit').removeClass('show');
                        attribute.find('.view').addClass('show');
                    });
                }

                function expand_item(item) {
                    item.find('.attribute').each(function() {
                        var attribute = $(this);
                        attribute.find('.view').removeClass('show');
                        attribute.find('.edit').addClass('show');
                    });
                }

                $('.checkbox input').mousedown(function(event) {
                    event.stopPropagation();
                    var item = $(this).closest('.list-item');
                    toggle_item(item);
                });

                $('.list-item').mousedown(function() {
                    if (!focused) {
                        toggle_item($(this));
                    }
                    else {
                        expand_item($(this));
                    }
                });
                
                $('html').mousedown(function() {
                    if (focused) {
                        collapse_item(focused);
                        focused = false;
                    }
                });

                $('.list-item').hover(function() {
                    if (!focused) {
                        expand_item($(this));
                    }
                }, function() {
                    if (!focused) {
                        collapse_item($(this));
                    }
                });

                $('.list-item .attribute .edit').mousedown(function(event) {
                    event.stopPropagation();
                });

                $('input').focus(function() {
                    focused = $(this).closest('.list-item');
                });

                $('input').blur(function(e) {
                    focused = false;
                });
            });
        </script>
    </head>
    <body>

        <div>
            <h1>Berthing</h1>
            <div style="margin-bottom: 10px;">
                Visitors' berths in this marina have been recently renovated.
            </div>

            <div class="list">

                <div class="list-item">
                    <div class="checkbox">
                        <input type="checkbox" />
                    </div>
                    <div class="label">
                        sea berths
                    </div>
                    <div class="attributes">

                        <div class="attribute">
                            <div class="view">
                                <div class="placeholder">number</div>
                                <div class="value"></div>
                            </div>
                            <div class="edit show">
                                <div class="hint">number</div>
                                <div class="input"><input type="text" style="width: 40px;" /></div>
                            </div>
                        </div>

                        <div class="attribute">
                            <div class="view">
                                <div style="float: left;">,&nbsp</div><div class="placeholder">type</div>
                                <div class="value"></div>
                            </div>
                            <div class="edit show">
                                <div class="hint">, type</div>
                                <div class="input"><input type="text" style="width: 100px;" /></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="list-item">
                    <div class="checkbox">
                        <input type="checkbox" />
                    </div>
                    <div class="label">
                        dry berths
                    </div>
                    <div class="attributes">

                        <div class="attribute">
                            <div class="view">
                                <div class="placeholder">number</div>
                                <div class="value"></div>
                            </div>
                            <div class="edit">
                                <div class="hint">number</div>
                                <div class="input"><input type="text" style="width: 40px;" /></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="list-item">
                    <div class="checkbox">
                        <input type="checkbox" />
                    </div>
                    <div class="label">
                        water
                    </div>
                    <div class="attributes">

                        <div class="attribute">
                            <div class="view">
                                <div class="placeholder">free/paid</div>
                                <div class="value"></div>
                            </div>
                            <div class="edit">
                                <div class="hint">free/paid</div>
                                <div class="input"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="list-item">
                    <div class="checkbox">
                        <input type="checkbox" />
                    </div>
                    <div class="label">
                        electricity
                    </div>
                </div>

            </div>

        </div>

    </body>
</html>
