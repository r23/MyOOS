jQuery(function ($) {
    var url = localized_object.url;

    //find checkbox
    //on document change dissable submit button when checkbox is not checked
    var gdpr_checkbox = $('input#gdpr');
    var comments_submit_button = $('#comments').find(':submit');
    //on document load disable button to add comments
    comments_submit_button.prop('disabled', true);
    comments_submit_button.addClass('gdpr-disabled');

    var data = {
        'action': localized_object.action,
        'action_switch': 'toggle_gdpr_checbox',
    };

    $(gdpr_checkbox).on('change', function (e) {
        if (gdpr_checkbox.prop('checked') === true) {
            data.gdpr_checked = true;
        }else{
            data.gdpr_checked = false;
        }
        data.gdpr_location = window.location.href;

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (data) {
            }
        });


    });

    //on checkbox add or remove disable on submit button
    $(gdpr_checkbox).on('click', function(){
        //when is clicked and has class disabled
        if(gdpr_checkbox.prop('checked')===true && comments_submit_button.hasClass('gdpr-disabled')) {
            // if checkbox is checked..
            comments_submit_button.removeProp('disabled');
            comments_submit_button.removeClass('gdpr-disabled');
        } else {
            // if checkbox is not checked..
            comments_submit_button.prop('disabled', true);
            comments_submit_button.addClass('gdpr-disabled');

        }
    });

    data.gdpr_checked = false;
    data.gdpr_location = window.location.href;

    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        success: function (data) {
        }
    });
});

