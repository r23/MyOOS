jQuery(function ($) {
    //accordion
    $("#accordion_core").accordion({
        collapsible: true,
        active: false,
        heightStyle: "content",
        autoHeight: false
    });
    $("#accordion_addons").accordion({
        collapsible: true,
        active: false,
        heightStyle: "content",
        autoHeight: false
    });
    //tabs faq
    $('ul.tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    });

    // Hide user info
    $('.user_info_header').click(function () {
        $('.user_info').hide()
    });
//slick carousel
if ($('.variable').length > 0) {
    $(".variable").slick({
        dots: false,
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
        variableWidth: true,
        centerMode: true,
    });
}

})
;

