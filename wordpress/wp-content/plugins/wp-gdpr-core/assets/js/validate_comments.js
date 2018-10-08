jQuery(function ($) {
    var url = localized_object.url;

    //find checkbox
    //on document change dissable submit button when checkbox is not checked
    var gdpr_checkbox = $('input#gdpr');
    var comments_submit_button = $('#comments').find(':submit');
    //on document load disable button to add comments
    comments_submit_button.prop('disabled', true);
    comments_submit_button.addClass('gdpr-disabled');

    $(document).on('change', function (e) {
        if (gdpr_checkbox.prop('checked') === true) {
            comments_submit_button.prop('disabled', false);
            comments_submit_button.removeClass('gdpr-disabled');

        }else{
            comments_submit_button.prop('disabled', true);
            comments_submit_button.addClass('gdpr-disabled');
        }
        //TODO add event listenter for disabled button
        //when is clicked and has class disabled
        //show info that user has to check checkbox to submit comment
    });
});

