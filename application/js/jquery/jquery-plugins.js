

$.fn.mapmenu = function() {

    if (arguments.length === 1 && typeof arguments[0] === 'string') {
        var op = arguments[0];
        $(this).each(function() {
            var menu = $(this);
            switch (op) {
                case 'hide':
                    {
                        menu.unbind();
                        menu.hide();
                        menu.find('.ps-ui-menu').hide();
                        menu.find('.ps-ui-menu li').unbind();
                        menu.find('li.ps-ui-menuitem-stayhover').removeClass('ps-ui-menuitem-stayhover');
                        break;
                    }
            }
        });
        return;
    }

    $('.ps-ui-menu').mapmenu('hide');

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

    // Unbind any previously attached listeners
    menu.unbind();
    menu.find('.ps-ui-menu li').unbind();

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

                //sm.css('top', item.position().top - 5);
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

    // If called more than once...
    if ($(this).next().hasClass('ps-ui-selectbutton')) {
        $(this).next().remove();
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
        var list = $('<ul class="ps-ui-selectbutton"></ul>');

        var hasDefaultOption = select.find('option:empty').length > 0;
        var defaultOption = select.find('option:empty').val();

        select.css('visibility', 'hidden');
        select.css('position', 'absolute');
        select.after(list);

        options.each(function() {

            var option = $(this);
            var value = option.val();
            var label = option.text();

            if (label !== '') {

                var item = $('<a class="ps-ui-selectbutton-item' + (option.is(':selected') ? ' ps-ui-selected' : '') + '" selectValue="' + value + '" href="">' + label + '</a>');
                list.append(item);

                // Button is clicked
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
                        } else if (hasDefaultOption) {
                            button.removeClass('ps-ui-selected');
                            select.val(defaultOption);
                        }
                    }

                    item.focus();
                    select.change();
                    e.preventDefault();
                });

                item.keydown(function(e) {
                    if (e.which === 32) {
                        item.click();
                        e.preventDefault();
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

        // If called more than once...
        if ($(this).next().hasClass('ps-ui-select')) {
            $(this).next().remove();
        }

        var select = $(this);
        var label = select.find('option:selected').text();
        var button = $('<a href="" class="ps-ui-select"><span class="ps-ui-select-label">' + label + '</span><span class="ps-ui-select-arrows"></span></a>');

        // Hide select
        select.css('visibility', 'hidden');
        select.css('position', 'absolute');

        // Insert button just after select
        button.insertAfter(select);

        button.focus(function() {
            $(this).addClass('ps-ui-focus');
        });

        button.blur(function() {
            $(this).removeClass('ps-ui-focus');
        });

        button.keydown(function(e) {
            if (e.which === 32 || e.which === 38 || e.which === 40) {
                $(this).trigger('click');
                e.preventDefault();
            }
        });

        button.click(function(e) {

            // Hide all other select lists
            $('.ps-ui-select-list').remove();

            var value = select.val();
            var selectList = $('<ul class="ps-ui-select-list"></ul>');
            var offset = 0;

            // Append list after the select button
            selectList.insertAfter($(this));

            select.find('option').each(function() {

                var option = $(this);
                var item = $('<a class="ps-ui-select-list-item" href="" data-value="' + option.val() + '">' + option.text() + '</a>');
                selectList.append(item);

                // Pre-select selected item
                if (value === $(this).val()) {
                    item.addClass('ps-ui-hover');
                    offset = item.position().top;
                    item.focus();
                }

                item.click(function(e) {

                    var oldValue = select.val();
                    var newValue = $(this).attr('data-value');
                    var newLabel = $(this).text();

                    select.val(newValue);
                    button.find('.ps-ui-select-label').text(newLabel);

                    if (oldValue !== newValue) {
                        select.trigger('change');
                    }

                    button.focus();
                    e.preventDefault();
                });

                item.mouseenter(function() {
                    $(this).siblings().removeClass('ps-ui-hover');
                    $(this).addClass('ps-ui-hover');
                });

                item.mouseleave(function() {
                    $(this).removeClass('ps-ui-hover');
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
                                } else {
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
                                } else {
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

            selectList.css('top', pos.top - offset - 1);
            selectList.css('left', pos.left - 9);

            $('html').one('click', function() {
                selectList.remove();
                button.focus();
            });

            e.preventDefault();
            e.stopPropagation();
        });
    });
};