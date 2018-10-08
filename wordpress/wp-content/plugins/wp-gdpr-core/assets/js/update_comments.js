jQuery(function ($) {
    var url = localized_object.url;

    var data = {
        'action': localized_object.action,
        'action_switch': 'edit_comment',
    };
    $('.js-comment-edit').on('change', function (e) {
        data.new_value = $(this).val();
        data.input_name = $(this).data('name');
        data.comment_id = $(this).data('id');

        //send ajax with changed data
        send_ajax_call(data);
    });

    function send_ajax_call(data) {
        /**
         * ajax call to controller-search-form.php
         * ajax registered in php as: flight_endpoint
         */
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
                $('.js-update-message').html(data);
            }
        });
    }
});

