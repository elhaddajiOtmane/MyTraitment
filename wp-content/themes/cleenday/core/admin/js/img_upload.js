'use strict';
(function($) {
    $('body').on('click','.cleenday_media_upload', function(e) {
        var mediaImg = $(this).parent('p').find('img');
        var mediaUrl = $(this).parent('p').find('.cleenday_media_url');

        e.preventDefault();
        var custom_uploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use This Image',
            },
            library: {
                type: 'image'
            },
            multiple: false,
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $(mediaImg).attr('src', attachment.url);
            $(mediaUrl).val(attachment.url);
        })
        .open();
    });


    $('body').on('click','.cleenday_media_upload_delete', function(e) {
        $(this).parent('p').find('img').attr('src', '');
        $(this).parent('p').find('.cleenday_media_url').val('');
    });


})(jQuery);