jQuery(document).on( 'click', '.shariff-update-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'shariffdismiss'
        }
    })

})
