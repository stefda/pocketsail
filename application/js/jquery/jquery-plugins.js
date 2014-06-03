
$.fn.multiButtonSelect = function(value) {
    var select = $(this);
    select.val(value);
    select.next().find('.ps-ui-selected').removeClass('ps-ui-selected');
    select.next().find('[selectValue=' + value + ']').addClass('ps-ui-selected');
    select.change();
    return;
};

$.fn.multiButton = function(o) {

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

        select.change(function() {
            var option = $(this).find('option:selected');
            var value = option.attr('value');
            var label = option.text();
            if (o.select !== undefined) {
                o.select.call(this, event, {
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
        //$(this).hide();
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
                        case 32:
                            {

                                break;
                            }
                    }
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