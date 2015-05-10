
function initUI() {

    // Style select inputs
    $('.tpl-select').select();

    // Style select-button inputs
    $('.tpl-select-button').selectButton();

    // Change border on focus
    $('.tpl-text-large,.tpl-details-small,.tpl-details-large').focus(function () {
        $(this).addClass('tpl-focus');
    });

    // Change border on blur
    $('.tpl-text-large,.tpl-text-small,.tpl-details-small,.tpl-details-large').blur(function () {
        $(this).removeClass('tpl-focus');
    });

    // Autosize all textareas
    $('textarea').autosize({
        append: false
    });

    // Contact delete button
    $('.tpl-delete-button').click(function () {
        $(this).closest('tr').remove();
    });

    // Details button
    $('.tpl-details-button').click(function (e) {
        var elem = $(this).closest('.tpl-has-details-button');
        if ($(this).hasClass('tpl-stay-visible')) {
            $(this).removeClass('tpl-stay-visible');
            elem.next('.tpl-details').hide();
        } else {
            $(this).addClass('tpl-stay-visible');
            var details = elem.next('.tpl-details').show();
            details.find('textarea').autosize().show().trigger('autosize.resize');
        }
        e.preventDefault();
    });

    $('.tpl-details textarea').keyup(function () {
        if ($(this).val() === '') {
            $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').removeClass('tpl-visible');
            $(this).removeClass('attr-include');
        } else {
            $(this).closest('.tpl-details').prev('.tpl-has-details-button').find('.tpl-details-button').addClass('tpl-visible');
            $(this).addClass('attr-include');
        }
    });
}