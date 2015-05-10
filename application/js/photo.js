
var init = false;
var onPhotosUpload = null;

function photosUploadFrameLoad() {
    if (!init) {
        init = true;
        return;
    }
    onPhotosUpload();
}

$(function () {

    /**
     * Define UI
     */

    var photosUploadFrame = $('#photosUploadFrame');
    var photosUploadForm = $('#photosUploadForm');
    var photosUploadButton = $('#photosUploadButton');
    var photosInput = $('#photosInput');
    var gallery = $('#gallery');
    var galleryButton = $('#galleryShowButton');
    var mainPhotoBounds = $('#mainPhotoBounds');
    var mainPhotoWrapper = $('#mainPhotoWrapper');
    var photosPreview = $('#photoPreview');
    var photoSettingsMenu = $('#photoSettingsMenu');

    /**
     * Flags
     */
    var galleryVisible = false;

    photosUploadButton.click(function () {
        photosInput.click();
    });

    photosInput.change(function () {
        photosUploadForm.submit();
    });

    onPhotosUpload = function () {

        var text = photosUploadFrame.contents().find('body').text();
        var res = $.parseJSON(text);

        if (res.status === 'OK') {
            showPhotoPreview(res.value.ids);
            if (res.value.main && res.value.main !== mainPhotoId) {
                showMainPhoto(res.value.main);
            }
            galleryButton.val('Hide all photos');
        } else {
            alert("Error: " + res.message);
        }
    };

    $('body').click(function () {
        photoSettingsMenu.hide();
    });

    galleryButton.click(function () {

        if (galleryVisible) {
            hidePhotoPreview();
            return;
        }

        PhotoBroker.get_infos({
            post: {
                'poiId': poiId
            },
            success: function (res) {
                showPhotoPreview(res.ids, res.descriptions);
            }
        });
    });

    function initMainPhoto(offset) {

        var photoHeight = mainPhotoWrapper.innerHeight();
        var galleryHeight = gallery.innerHeight();
        var photoBoundsHeight = photoHeight + photoHeight - galleryHeight;
        var photoBoundsOffset = -Math.ceil(photoHeight - galleryHeight);

        mainPhotoBounds.innerHeight(photoBoundsHeight);
        mainPhotoBounds.css('top', photoBoundsOffset);

        if (offset === undefined) {
            mainPhotoWrapper.css('top', (-photoBoundsOffset / 2) + 'px');
        } else if (offset !== 'default') {
            mainPhotoWrapper.css('top', offset + 'px');
        }

        mainPhotoWrapper.draggable({
            axis: 'y',
            containment: mainPhotoBounds,
            stop: function () {
                var offset = mainPhotoWrapper.position().top;
                setPhotoOffset(mainPhotoId, Math.round(offset));
            }
        });
    }

    function showPhotoPreview(ids, descriptions) {

        // Clear preview contents
        photosPreview.html('');

        for (var i = 0; i < ids.length; i++) {
            if (i % 4 === 0) {
                photosPreview.append('<div style="clear: both;">');
            }
            photosPreview.append(
                    '<div class="photo" style="margin-left: ' + (i % 4 === 0 ? '0' : '12px') + '">' +
                    '<span class="photoSettings" data-id="' + ids[i] + '"></span>' +
                    '<img src="/data/photos/preview/' + ids[i] + '.jpg" />' +
                    '<input type="hidden" name="id[]" value="' + ids[i] + '" />' +
                    '<textarea placeholder="Enter description" class="photoDescription" name="description" data-id="' + ids[i] + '">' + (descriptions !== undefined ? descriptions[i] : '') + '</textarea><br />' +
                    '<div>');
            if (i % 4 === 3) {
                photosPreview.append('</div>');
            }
        }

        $('.photoDescription').autosize({
            'append': false
        });

        photosPreview.on('blur', '.photoDescription', updatePhotoDescription);
        photosPreview.on('click', '.photoSettings', photoSettingsClick);

        photosPreview.show();
        galleryButton.val('Hide all photos');
        galleryVisible = true;
    }

    function hidePhotoPreview() {
        photosPreview.hide();
        photosPreview.html('');
        galleryButton.val('Show all photos');
        galleryVisible = false;
    }

    function updatePhotoDescription() {

        var id = $(this).data('id');
        var description = $(this).val();

        PhotoBroker.set_description({
            post: {
                'id': id,
                'description': description
            },
            success: function () {
                console.log('Description updated.');
            }
        });
    }

    function photoSettingsClick(e) {

        e.preventDefault();
        e.stopPropagation();

        var id = $(this).data('id');
        var photo = $(this);

        photoSettingsMenu.mapmenu({
            top: e.pageY,
            left: e.pageX,
            select: function (e, ui) {
                var action = ui.item.value;
                switch (action) {
                    case 'main':
                    {
                        setMainPhoto(id);
                        break;
                    }
                    case 'rotate-right':
                    {
                        rotatePhoto(id, 'right', photo);
                        break;
                    }
                    case 'rotate-left':
                    {
                        rotatePhoto(id, 'left', photo);
                        break;
                    }
                    case 'delete':
                    {
                        if (confirm('Deleting the photo cannot be undone! Continue?')) {
                            deletePhoto(id, poiId);
                        }
                        break;
                    }
                }
                photoSettingsMenu.hide();
            }
        });
    }

    function setPhotoOffset(photoId, offset) {
        PhotoBroker.set_offset({
            post: {
                id: photoId,
                offset: offset
            },
            success: function (res) {
                console.log('Photo offset set.');
            }
        });
    }

    function setMainPhoto(id) {
        PhotoBroker.set_main({
            post: {
                'id': id
            },
            success: function () {
                showMainPhoto(id);
            }
        });
    }

    function rotatePhoto(id, dir, photo) {
        PhotoBroker.rotate({
            post: {
                'id': id,
                'dir': dir
            },
            success: function () {
                photo.closest('.photo').find('img').attr('src', '/data/photos/preview/' + id + '.jpg?' + new Date().getTime());
            }
        });
    }

    function deletePhoto(id, poiId) {
        PhotoBroker.delete({
            post: {
                id: id,
                poiId: poiId
            },
            success: function (res) {

                if (res.main !== false && res.main !== mainPhotoId) {
                    showMainPhoto(res.main);
                }

                if (res.ids.length === 0) {
                    hidePhotoPreview();
                    clearMainPhoto();
                } else {
                    showPhotoPreview(res.ids);
                }
            }
        });
    }

    function showMainPhoto(id) {

        mainPhotoId = id;
        var img = $('<img id="galleryPhoto" src="/data/photos/gallery/' + id + '.jpg" />');

        img.load(function () {
            initMainPhoto();
        });

        mainPhotoWrapper.html('');
        mainPhotoWrapper.append(img);
    }

    function clearMainPhoto() {
        mainPhotoId = undefined;
        mainPhotoWrapper.html('');
    }

    $('#galleryPhoto').load(function () {
        initMainPhoto('default');
    }).each(function () {
        if (this.complete) {
            $(this).load();
        }
    });
});