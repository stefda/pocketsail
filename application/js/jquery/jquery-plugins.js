

$.fn.menu = function() {

    if (arguments.length === 1 && typeof arguments[0] === 'string') {
        var op = arguments[0];
        $(this).each(function() {
            var menu = $(this);
            switch (op) {
                case 'hide':
                    {
                        menu.hide();
                        menu.find('.ps-ui-menu').hide();
                        menu.find('li.ps-ui-menuitem-stayhover').removeClass('ps-ui-menuitem-stayhover');
                        break;
                    }
            }
        });
        return;
    }

    // Assign params object
    var o = arguments[0];

    // Normalise params object
    if (o === undefined) {
        o = {};
    }

    var menu = $(this);
    var top = 0;
    var left = 0;
    var selectFx = null;

    // Add ui menu class
    menu.find('ul').add(menu).addClass('ps-ui-menu');

    // Add submenu arrows to all items with submenbus
    menu.find('li').has('.ps-ui-menu').prepend('<span class="ps-ui-submenu-arrow"></span>');

    // Define global vtimeout reference
    window.psUIMenuOpenT = null;
    window.psUIMenuCloseT = null;

    menu.on('mouseover', 'ul.ps-ui-menu', function(e) {
        e.stopPropagation();
    });

    menu.on('mouseover', 'li', function(e) {

        // Don't propagate menu clicks
        e.stopPropagation();

        // Clear any menu timeouts
        clearTimeout(psUIMenuCloseT);
        clearTimeout(psUIMenuOpenT);

        var item = $(this);
        var openSubMenus = item.siblings().find('.ps-ui-menu:visible');

        // Ensure both hovered item and its parent stay hover
        item.addClass('ps-ui-menuitem-hover');
        item.parent().closest('li').addClass('ps-ui-menuitem-stayhover');

        // If any other subenus are open then schedule their close
        if (openSubMenus.length) {
            openSubMenus.parent('li').removeClass('ps-ui-menuitem-stayhover');
            psUIMenuCloseT = setTimeout(function() {
                openSubMenus.hide();
                psUIMenuCloseT = null;
            }, 300);
        }

        if (item.find('.ps-ui-menu').length) {

            psUIMenuOpenT = setTimeout(function() {

                var sm = item.children('.ps-ui-menu');

                sm.find('.ps-ui-menu').hide();
                sm.find('.ps-ui-menuitem-stayhover').removeClass('ps-ui-menuitem-stayhover');
                sm.find('.ps-ui-menu .ps-ui-menuitem-stayhover').removeClass('ps-ui-menuitem-stayhover');

                var itemPos = item.position();
                var itemOffset = item.offset();
                var winHeight = $(window).outerHeight();
                var docTop = $(document).scrollTop();
                var docBottom = docTop + winHeight;

                var smHeight = sm.outerHeight();
                var smOffsetTop = itemOffset.top - 5;
                var smOffsetBottom = smOffsetTop + smHeight;
                var smTop = itemPos.top - 5;

                // Shift submenu up if need be
                if (smOffsetBottom > docBottom) {
                    smTop -= smOffsetBottom - docBottom;
                    smOffsetTop -= smOffsetBottom - docBottom;
                }

                // Shift submenu down if need be
                if (docTop > smOffsetTop) {
                    smTop += docTop - smOffsetTop;
                }

                item.addClass('ps-ui-menuitem-stayhover');
                sm.css('top', item.position().top - 5);
                sm.css('left', item.width() + 45);
                sm.css('top', smTop);
                sm.show();
            }, 300);
        }
    });

    menu.on('mouseout', 'li', function(e) {
        $(this).removeClass('ps-ui-menuitem-hover');
        clearTimeout(psUIMenuOpenT);
    });

    menu.on('mouseup', 'li', function(e) {
        var item = $(this);
        var val = item.attr('data-value');
        if (selectFx !== null) {
            selectFx.call(this, e, {
                item: {
                    value: val
                }
            });
        }
        e.stopPropagation();
    });

    if (o.select !== undefined) {
        selectFx = o.select;
    }

    top = o.top === undefined ? top : o.top;
    left = o.left === undefined ? left : o.left;

    var winHeight = $(window).outerHeight();
    var docTop = $(document).scrollTop();
    var docBottom = docTop + winHeight;

    var menuOffsetBottom = top + menu.outerHeight();

    if (menuOffsetBottom > docBottom) {
        top -= menuOffsetBottom - docBottom;
    }

    if (docTop > top) {
        top += docTop - top;
    }

    // Finally, position menu and show
    menu.css('top', top + 'px');
    menu.css('left', left + 1 + 'px');
    menu.show();
};

$.fn.selectButton = function() {

    // Perform user operation
    if (arguments.length === 2 && typeof arguments[0] === 'string') {
        var op = arguments[0];
        $(this).each(function() {
            var select = $(this);
            switch (op) {
                case 'select':
                    {
                        var value = arguments[1];
                        select.val(value);
                        select.next().find('.ps-ui-selected').removeClass('ps-ui-selected');
                        select.next().find('[selectValue=' + value + ']').addClass('ps-ui-selected');
                        select.change();
                        break;
                    }
            }
        });
        return;
    }

    // Assign params object with the first argument
    var o = arguments[0];

    // Fix params object if undefined
    if (o === undefined) {
        o = {};
    }

    $(this).each(function() {

        var select = $(this);
        var multiple = select.prop('multiple');
        var options = select.find('option');
        var list = $('<ul class="ps-ui-multibutton"></ul>');

        select.css('visibility', 'hidden');
        select.css('position', 'absolute');
        select.after(list);

        options.each(function() {

            var option = $(this);
            var value = option.val();
            var label = option.text();

            if (label !== '') {
                var item = $('<a class="ps-ui-multibutton-item' + (option.is(':selected') ? ' ps-ui-selected' : '') + '" selectValue="' + value + '" href="">' + label + '</a>');
                list.append(item);
                item.click(function(e) {

                    var button = $(this);
                    var value = button.attr('selectValue');
                    var option = select.find('option[value=' + value + ']');

                    if (multiple) {
                        if (option.prop('selected')) {
                            option.prop('selected', false);
                            button.removeClass('ps-ui-selected');
                        } else {
                            option.prop('selected', true);
                            button.addClass('ps-ui-selected');
                        }
                    } else {
                        var selectedButton = list.find('.ps-ui-selected');
                        if (!selectedButton.is(button)) {
                            button.addClass('ps-ui-selected');
                            selectedButton.removeClass('ps-ui-selected');
                            select.val(value);
                        }
                    }
                    select.change();
                    e.preventDefault();
                });

                item.keydown(function(e) {
                    if (e.which === 32) {
                        item.click();
                    }
                });

                item.focus(function() {
                    $(this).addClass('ps-ui-focus');
                });

                item.blur(function() {
                    $(this).removeClass('ps-ui-focus');
                });
            }
        });

        select.change(function(e) {

            var option = $(this).find('option:selected');
            var value = option.attr('value');
            var label = option.text();

            if (o.select !== undefined) {
                o.select.call(this, e, {
                    item: {
                        value: value,
                        label: label
                    }
                });
            }
        });
    });
};
jQuery.fn.select = function() {

    $(this).each(function() {

        var text = $(this).find('option:selected').text();
        var choose = $('<a href="" class="ps-ui-select">' + text + '</a>');

        $(this).css('visibility', 'hidden');
        $(this).css('position', 'absolute');

        choose.insertAfter($(this));

        choose.focus(function() {
            $(this).addClass('hover');
        });

        choose.blur(function() {
            $(this).removeClass('hover');
        });

        choose.keydown(function(e) {
            if (e.which === 32 || e.which === 38 || e.which === 40) {
                $(this).trigger('click');
                $(this).blur();
                e.preventDefault();
            }
        });

        choose.click(function(e) {
            e.preventDefault();
            var select = $(this).prev();
            var val = select.val();
            var wrap = $('<ul class="ps-ui-select-list"></ul>');
            var offset = 0;
            $(this).parent().append(wrap);
            select.find('option').each(function() {
                var item = $('<a href="">' + $(this).text() + '</a>');
                item.attr('value', $(this).val());
                wrap.append(item);
                if (val === $(this).val()) {
                    item.addClass('hover');
                    offset = item.position().top;
                    item.focus();
                }
                item.click(function(e) {
                    e.preventDefault();
                    select.val($(this).attr('value'));
                    choose.text($(this).text());
                });
                item.hover(function() {
                    $(this).siblings().removeClass('hover');
                    $(this).addClass('hover');
                }, function() {
                    $(this).removeClass('hover');
                });
                item.keydown(function(e) {
                    switch (e.which) {
                        case 13:
                        case 32:
                            {
                                $(this).click();
                                break;
                            }
                        case 40:
                            {
                                var next = null;
                                if ($(this).next().length === 0) {
                                    next = $(this).parent().children(':first');
                                }
                                else {
                                    next = $(this).next();
                                }
                                $(this).trigger('mouseleave');
                                next.trigger('mouseenter');
                                next.focus();
                                break;
                            }
                        case 38:
                            {
                                var prev = null;
                                if ($(this).prev().length === 0) {
                                    prev = $(this).parent().children(':last');
                                }
                                else {
                                    prev = $(this).prev();
                                }
                                $(this).trigger('mouseleave');
                                prev.trigger('mouseenter');
                                prev.focus();
                                break;
                            }
                    }
                    e.preventDefault();
                });
            });
            var pos = $(this).position();
            wrap.css('top', pos.top - offset - 1);
            wrap.css('left', pos.left - 9);
            $('html').one('click', function() {
                wrap.hide();
                choose.focus();
            });
            e.stopPropagation();
        });
    });
};