
$(function () {

    /**
     * Define UI
     */

    var canvasResizeButton = $('#canvasResizeButton');
    var catSelectButton = $('#catSelectButton');
    var subSelectButton = $('#subSelectButton');
    var cancelButton = $('#cancelButton');
    var saveButton = $('#saveButton');
    var boxhead = $('#boxhead');
    var gallery = $('#gallery');

    canvasResizeButton.click(function () {

        var center = map.getCenter();

        if (boxhead.hasClass('boxhead-expanded')) {
            boxhead.removeClass('boxhead-expanded');
            gallery.show();
        } else {
            gallery.hide();
            boxhead.addClass('boxhead-expanded');
        }

        map.resize();
        map.setCenter(center);
    });

    // Load subs for changed cat
    catSelectButton.change(function () {

        POIBroker.getSubs({
            post: {
                cat: $(this).val()
            },
            success: function (subs) {
                subSelectButton.empty();
                for (var i = 0; i < subs.length; i++) {
                    var sub = subs[i];
                    subSelectButton.append('<option value="' + sub.id + '">' + sub.name + '</option>');
                }
                initUI();
                subSelectButton.trigger('change');
            }
        });
    });

    // Change template when sub is changed
    subSelectButton.change(function () {

        var name = $('[name=name]').val();
        var label = $('[name=label]').val();
        var attrs = $('.attr:visible,.attr-include').serialize();

        POIBroker.getTemplate({
            post: $.param({
                cat: catSelectButton.val(),
                sub: $(this).val(),
                name: name,
                label: label
            }) + '&' + attrs,
            success: function (html) {
                var data = $(html);
                var left = data.find('.tpl-column-left .replace');
                var right = data.find('.tpl-column-right .replace');
                $('body').find('.tpl-column-left .replace').html(left.html());
                $('body').find('.tpl-column-right .replace').html(right.html());
                initUI();
            }
        });
    });

    validator.add(function () {

        var cat = $('[name=cat]').val().trim();
        var sub = $('[name=sub]').val().trim();
        var nearId = $('[name=nearId]').val();
        var countryId = $('[name=countryId]').val();

        if (this.valid && (cat === 'geo' || cat === 'admin' || sub === 'marina') && !map.hasBorderPolygon()) {
            this.valid = confirm('Do you wish to save this POI without a border?');
        }

        if (this.valid && (nearId === '' || countryId === '')) {
            this.valid = confirm('Do you wish to save this POI without a near place and/or country?');
        }

        return this.valid;
    });

    cancelButton.click(function () {
        window.location = '/';
    });

    saveButton.click(function (e) {

        e.preventDefault();
        e.stopPropagation();

        var cat = $('[name=cat]').val().trim();
        var sub = $('[name=sub]').val().trim();
        var nearId = $('[name=nearId]').val();
        var countryId = $('[name=countryId]').val();
        var name = $('[name=name]').val().trim();
        var label = $('[name=label]').val().trim();
        var url = $('[name=url]').val().trim();
        var attrs = $('.attr:visible,.attr-include').serialize();

        if (map.hasBorderPolygon()) {
            var border = map.getBorderPolygon().toGeoJson();
        }

        if (map.hasDraggableMarker()) {
            var latLng = map.getDraggableMarkerLatLng().toGeoJson();
        }

        if (validator.validate()) {
            APIBroker.updatePoi({
                post: $.param({
                    poiId: poiId,
                    name: name,
                    label: label,
                    url: url,
                    nearId: nearId,
                    countryId: countryId,
                    cat: cat,
                    sub: sub,
                    latLng: latLng,
                    border: border
                }) + '&' + attrs,
                success: function (res) {
                    if (res) {
                        window.location = '/';
                    }
                }
            });
        }
    });
});