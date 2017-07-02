

var App = function () {

    function handleBootstrap() {
        /*Bootstrap Carousel*/
        jQuery('.carousel').carousel({
            interval: 15000,
            pause: 'hover'
        });

    }

    function handleSearch() {
        jQuery('.search-button').click(function () {
            jQuery('.search-open').slideDown();
        });

        jQuery('.search-close').click(function () {
            jQuery('.search-open').slideUp();
        });

        jQuery(window).scroll(function(){
          if(jQuery(this).scrollTop() > 1) jQuery('.search-open').fadeOut('fast');
        });

    }

    function handleToggle() {
        jQuery('.list-toggle').on('click', function() {
            jQuery(this).toggleClass('active');
        });
    }

    function handleHeader() {
         jQuery(window).scroll(function() {
            if (jQuery(window).scrollTop()>100){
                jQuery(".header-fixed .header-static").addClass("header-fixed-shrink");
            }
            else {
                jQuery(".header-fixed .header-static").removeClass("header-fixed-shrink");
            }
        });
    }


    return {
        init: function () {
            handleBootstrap();
            handleSearch();
            handleToggle();
            handleHeader();
        },

        initScrollBar: function () {
            jQuery('.mCustomScrollbar').mCustomScrollbar({
                theme:"minimal",
                scrollInertia: 300,
                scrollEasing: "linear"
            });
        },

    };

}();
