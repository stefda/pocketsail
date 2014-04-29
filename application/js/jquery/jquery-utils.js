
jQuery._inputAid = null;

jQuery.fn.inputAid = function(html, action) {

    if (jQuery._inputAid != null) {
        jQuery._inputAid.hide();
        jQuery._inputAid = null;
    }

    if (action != undefined && action == 'close') {
        return;
    }

    var position = $(this).offset();
    var top = position.top;
    var left = position.left;
    var divWrapper = $('<div class="inputAid-wrapper" style="opacity: 0; position: absolute;"><div class="inputAid-content" style="padding: 10px; width: 200px;">' + html + '</div></div>');
    var divArrow = $('<div class="inputAid-arrow" style="position: absolute; right: -31px;"><img src="/application/images/inputaid-arrow.png" /></div>')
    divWrapper.append(divArrow);
    $('body').append(divWrapper);
    divWrapper.css('left', 20);
    var height = divWrapper.outerHeight();
    var width = divWrapper.outerWidth();
    var inputHeight = $(this).outerHeight();
    divArrow.css('top', height / 2 - 13);
    divWrapper.css('top', top + inputHeight / 2 - height / 2);
    divWrapper.css('left', left - width - 19);
    jQuery._inputAid = divWrapper;
    divWrapper.animate({
        opacity: 1
    }, 80);
    return $(this);
}

jQuery._bubble = null;

jQuery.fn.bubble = function(html, type, action) {

    if (jQuery._bubble !== null) {
        jQuery._bubble.remove();
        jQuery._bubble = null;
    }

    if (action !== undefined && action === 'close') {
        return;
    }

    var target = $(this);
    var tOffset = target.offset();
    var bubble = $('<div class="bubbleWrapper"><div class="bubbleArrow"></div><div class="bubbleContent">' + html + '</div></div>');
    var arrow = bubble.find('.bubbleArrow');
    $('body').append(bubble);
    bubble.click(function(e) {
        e.stopPropagation();
    });
    
    var p = type.split('_');
    arrow.addClass(p[0].toLowerCase());

    var targetWidth = target.outerWidth();
    var targetHeight = target.outerHeight();
    var tipWidth = bubble.outerWidth();
    var tipHeight = bubble.outerHeight();
    var arrowWidth = arrow.outerWidth();
    var arrowHeight = arrow.outerHeight();

    var wox = 0;
    var woy = 0;
    var msfx = 0;
    var msfy = 0;

//    if ($.browser.msie) {
//        msfx = 2;
//        msfy = 2;
//    }

    var sh = 5;
    var sv = 6;

    switch (p[0]) {
        case 'TOP':
            {
                woy = targetHeight + arrowHeight + sh;
                arrow.css('top', -arrowHeight + msfy);
                break;
            }
        case 'RIGHT':
            {
                wox = -(tipWidth + arrowWidth + sv);
                arrow.css('right', -arrowWidth - msfx);
                break;
            }
    }

    switch (p[1]) {
        case 'LEFT':
            {
                wox = targetWidth / 2 - arrowWidth / 2 - sv;
                arrow.css('left', sv);
                break;
            }
        case 'CENTER':
            {
                wox = targetWidth / 2 - tipWidth / 2;
                arrow.css('left', tipWidth / 2 - arrowWidth / 2);
                break;
            }
        case 'RIGHT':
            {
                wox = targetWidth / 2 - tipWidth + arrowWidth / 2 + sv;
                arrow.css('left', tipWidth - arrowWidth - sv);
                break;
            }
        case 'TOP':
            {
                woy = targetHeight / 2 - arrowHeight / 2 - sh;
                arrow.css('top', sh);
                break;
            }
        case 'MIDDLE':
            {
                woy = targetHeight / 2 - tipHeight / 2;
                arrow.css('top', tipHeight / 2 - arrowHeight / 2);
                break;
            }
        case 'BOTTOM':
            {
                woy = targetHeight / 2 - tipHeight + arrowHeight / 2 + sh;
                arrow.css('top', tipHeight - arrowHeight - sh);
                break;
            }
    }

    bubble.css({top: tOffset.top + woy, left: tOffset.left + wox});
    jQuery._bubble = bubble;
};