(function($) {
    'use strict';

    $(function() {
        const prefix = $('.data-wrap').data('prefix'); 
        const settings = window[prefix + '_vars'];
console.log("KARAMBIT JS LOADED");
        // THE FIX: Use $form consistently
        const $form = $('.' + prefix + '_admin_form');
        
        $form.on('submit', function(e) {
            e.preventDefault();
            const formData = {};
            $form.serializeArray().forEach(item => {
                formData[item.name] = item.value;
            });
            // Visual feedback: disable button
            const $submitBtn = $form.find('input[type="submit"], button[type="submit"]');
            $submitBtn.prop('disabled', true).addClass('updating');

            $.ajax({
                url: settings.rest_url + 'settings',
                method: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', settings.nonce);
                },
                contentType: 'application/json; charset=utf-8',
                data: JSON.stringify(formData),
                success: function(response) {
                    const anchor = $('#' + settings.prefix + '_notices');
                    anchor.html( response.message ).hide().fadeIn();
                    $(document).trigger('wp-updates-notice-added');
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Critical Server Error';
                    const anchor = $('#' + settings.prefix + '_notices');
                    anchor.html('<div class="notice notice-error"><p>' + errorMsg + '</p></div>').hide().fadeIn();
                    $(document).trigger('wp-updates-notice-added');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).removeClass('updating');
                }
            });
        });
        let mediaFrame;
        $('.' + settings.prefix + '-select-img').on('click', function(e) {
            e.preventDefault();
            // console.log(this);
            let $button = $(this);
            let $parent = $button.closest('.' + settings.prefix + '-image-preview-wrapper');
            console.log($parent);
            let $removeButton = $parent.find('.' + settings.prefix + '-remove-img');
            console.log($parent);
            let $previewImg = $parent.find('.' + settings.prefix + '-image-preview');
            console.log($previewImg);
            let $hiddenInput = $parent.find('.' + settings.prefix + '-image-id');
            // If the frame exists, just open it
            if (mediaFrame) {
                mediaFrame.off('select');
            }

            // Create the media frame object
            mediaFrame = wp.media({
                title: 'Select Image',
                button: { text: 'Use this image' },
                multiple: false // Only one image allowed
            });

            // "Select" event handler
            mediaFrame.on('select', function() {
                const attachment = mediaFrame.state().get('selection').first().toJSON();
                
                // 1. Update the hidden input with the ID (The Schema)
                $hiddenInput.val( attachment.id );

                // 2. Update the Preview (The UI)
                $previewImg.attr('src', attachment.url); 
                $previewImg.show();
                $removeButton.show();
            });

            mediaFrame.open();
        });

        $('.' + settings.prefix + '-remove-img').on('click', function(e) {
            e.preventDefault();
            const $parent = $(this).closest('.' + settings.prefix + '-image-preview-wrapper');
            $parent.find('.' + settings.prefix + '-image-id').val('');
            $parent.find('.' + settings.prefix + '-image-preview').hide();
            $(this).hide();
        });

    });

   

})(jQuery);


