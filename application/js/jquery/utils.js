
function set_cookie(name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays === null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = name + "=" + c_value + ';path=/';
}

function get_cookie(name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x === name) {
            return unescape(y);
        }
    }
    return false;
}

function pad(str, max) {
    str += '';
    return str.length < max ? pad('0' + str, max) : str;
}

if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    };
}

jQuery._dialog = null;
jQuery._dialogParent = null;
jQuery.fn.dialog = function() {

    var options = {};
    if (arguments.length > 0) {
        options = arguments[0];
    }

    var left = options.left !== undefined ? options.left : 0;
    var top = options.top !== undefined ? options.top : 0;
    var arrow = options.arrow !== undefined ? options.arrow : 'none';

    if (jQuery._dialog === null) {
        jQuery._dialog = $('<div class="dialog"><div class="dialog-wrapper"><div class="dialog-content"></div></div></div>');
        $('body').append(jQuery._dialog);
    }

    if (typeof options === 'string' && options === 'close') {
        jQuery._dialog.hide();
        return null;
    }

    if (jQuery._dialogParent !== null) {
        var oldContent = jQuery._dialog.find('.dialog-content').children();
        oldContent.hide();
        $('body').append(oldContent);
    }

    jQuery._dialog.find('.dialog-top-arrow-wrapper').remove();
    jQuery._dialog.find('.dialog-bottom-arrow-wrapper').remove();

    switch (arrow) {
        case 'top':
            jQuery._dialog.find('.dialog-wrapper').prepend('<div class="dialog-top-arrow-wrapper"><div class="dialog-top-arrow"></div></div>');
            break;
        case 'bottom':
            jQuery._dialog.find('.dialog-wrapper').append('<div class="dialog-bottom-arrow-wrapper"><div class="dialog-bottom-arrow"></div></div>');
            break;
        default:
            break;
    }

    var newContent = $(this);
    jQuery._dialogParent = newContent.parent();
    $(this).detach().prependTo(jQuery._dialog.find('.dialog-content'));

    newContent.show();
    var dialogHeight = jQuery._dialog.outerHeight();
    var dialogWidth = jQuery._dialog.outerWidth();
    var dialogTop = 100;
    var dialogLeft = 100;
    switch (options.arrow) {
        case  'bottom':
            dialogTop = top - dialogHeight - 7;
            dialogLeft = left - 20;
            break;
        case 'top':
            dialogTop = top + 7;
            dialogLeft = left - dialogWidth + 27;
            break;
        default:
            dialogTop = top - dialogHeight / 2;
            dialogLeft = left - dialogWidth / 2;
            break;
    }
    jQuery._dialog.css('top', dialogTop);
    jQuery._dialog.css('left', dialogLeft);
    jQuery._dialog.show();
    return $(this);
};

jQuery._loader = null;
jQuery.fn.loader = function() {

    if (arguments.length === 0) {
        return false;
    }

    var counter = 1;
    var text = arguments[0];
    $(this).append($('<span>' + text + '<span id="_loaderEllipsis">.</span></span>'));
    setInterval(function() {
        counter = ++counter % 4;
        var ellipsis = '';
        for (var i = 0; i < counter; i++) {
            ellipsis += '.';
        }
        $('#_loaderEllipsis').text(ellipsis);

    }, 500);
    jQuery._loader = $(this);
    return $(this);
};

jQuery.fn.show_input_error = function(message) {
    var inputErrorMessage = $(this).siblings('.inputErrorMessage');
    if (message !== undefined) {
        inputErrorMessage.text(message);
    }
    $(this).addClass('inputError');
    inputErrorMessage.show();
    return $(this);
};

jQuery.fn.hide_input_error = function() {
    var inputErrorMessage = $(this).siblings('.inputErrorMessage');
    $(this).removeClass('inputError');
    inputErrorMessage.hide();
    return $(this);
};

jQuery.fn.iframe = function(o) {

    if (o.success !== undefined) {
        $(this).load(function() {
            var text = $(this).contents().find('body').text();
            var res = $.parseJSON(text);
            if (res.type === 'return') {
                o.success(res.data.value);
            }
            else {
                alert(res.data.errstr);
            }
        });
    }
};

jQuery.fn.ynk = function() {
    $(this).each(function() {
        var val = $(this).val();
        var ynk = $('<div class="button-ynn"><a href="" class="nk" value="nk" title="Not known">?</a><a href="" class="yes" value="yes" title="Yes">Yes</a><a href="" class="no" value="no" title="No">No</a></div>');
        $(this).hide();
        var _this = $(this);
        ynk.find('a[value=' + val + ']').addClass('selected');
        ynk.find('a').click(function(e) {
            e.preventDefault();
            $(this).siblings('.selected').removeClass('selected');
            $(this).addClass('selected');
            _this.val($(this).attr('value'));
            $(this).focus();
        });
        ynk.find('a').focus(function(e) {
            $(this).addClass('hover');
        });
        ynk.find('a').blur(function(e) {
            $(this).removeClass('hover');
        });
        ynk.insertAfter($(this));
    });
}

jQuery.fn.select = function() {
    $(this).each(function() {
        var text = $(this).find('option:selected').text();
        var choose = $('<a href="" class="select">' + text + '</a>');
        $(this).hide();
        choose.insertAfter($(this));
        choose.focus(function() {
            $(this).addClass('hover');
        });
        choose.blur(function() {
            $(this).removeClass('hover');
        });
        choose.keydown(function(e) {
            if (e.which == 32 || e.which == 38 || e.which == 40) {
                $(this).trigger('click');
                $(this).blur();
            }
        });
        choose.click(function(e) {
            e.preventDefault();
            var select = $(this).prev();
            var val = select.val();
            var wrap = $('<ul class="selectList"></ul>');
            var offset = 0;
            $(this).parent().append(wrap);
            select.find('option').each(function() {
                var item = $('<a href="">' + $(this).text() + '</a>');
                item.attr('value', $(this).val());
                wrap.append(item);
                if (val == $(this).val()) {
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
                        case 40: {
                            var next = null;
                            if ($(this).next().length == 0) {
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
                        case 38: {
                            var prev = null;
                            if ($(this).prev().length == 0) {
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
                        case 32: {
                                
                            break;
                        }
                    }
                });
            });
            var pos = $(this).position();
            wrap.css('top', pos.top - offset - 2);
            wrap.css('left', pos.left - 9);
            $('html').one('click', function() {
                wrap.hide();
                choose.focus();
            });
            e.stopPropagation();
        });
    });
}

function strip_tags(html) {
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText;
}