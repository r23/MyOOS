
	window.width 	= jQuery(window).width();
	window.height 	= jQuery(window).height();

	/* Init */
	jQuery(window).ready(function () {


		// jQuery 3.x do no support size() - should be replaceced with .legth
		jQuery.fn.extend({
		  size: function() {
		    return this.length;
		  }
		});

		// Init
		Init(false);


	});



/** Init
	Ajax Reinit:		Init(true);
 **************************************************************** **/
	function Init(is_ajax) {

		// First Load Only
		if(is_ajax != true) {

			_sideNav();
			_megaNavHorizontal();
	
		}
		_owl_carousel();
		_lightbox();

	}
	





/**  Side Nav
 **************************************************************** **/
	function _sideNav() {


		/* Mobile Button */
		jQuery("div.side-nav").each(function() {
			var _t = jQuery('ul', this);
			jQuery('button', this).bind("click", function() {
				_t.slideToggle(300);
			});
		});


		/* Submenus */
		jQuery("div.side-nav li>a.dropdown-toggle").bind("click", function(e) {
			e.preventDefault();

			jQuery(this).next('ul').slideToggle(200);
			jQuery(this).closest('li').toggleClass('active');
		});

	}



/** Mega Horizontal Navigation
 **************************************************************** **/
	function _megaNavHorizontal() {

		// WRAPPER MAIN MENU
		if(jQuery("#wrapper nav.main-nav").length > 0) {

			var _sliderWidth 	= jQuery("#slider").width(),
				_sliderHeight 	= jQuery("#wrapper nav.main-nav").height();

			// Submenu widh & height
			jQuery("#wrapper nav.main-nav>div>ul>li>.main-nav-submenu").css({"min-height":_sliderHeight+"px"});
			jQuery("#wrapper nav.main-nav>div>ul>li.main-nav-expanded>.main-nav-submenu").css({"width":_sliderWidth+"px"});

			// SUBMENUS
			jQuery("#wrapper nav.main-nav>div>ul>li").bind("click", function(e) {
				var _this = jQuery(this);

				if(!jQuery('div', _this).hasClass('main-nav-open')) {
					jQuery("#wrapper nav.main-nav>div>ul>li>.main-nav-submenu").removeClass('main-nav-open');
				}

				jQuery('div', _this).toggleClass('main-nav-open');
			});

		}






		// HEADER MAIN MENU
		var _hsliderWidth 	= jQuery("#header>.container").width() - 278,
			_hsliderHeight 	= jQuery("#header nav.main-nav").height();

		// Submenu widh & height
		jQuery("#header nav.main-nav>div>ul>li>.main-nav-submenu").css({"min-height":_hsliderHeight+"px"});
		jQuery("#header nav.main-nav>div>ul>li.main-nav-expanded>.main-nav-submenu").css({"width":_hsliderWidth+"px"});


		// SUBMENUS
		jQuery("#header nav.main-nav>div>ul>li").bind("click", function(e) {
			var _this = jQuery(this);

			if(!jQuery('div', _this).hasClass('main-nav-open')) {
				jQuery("#header nav.main-nav>div>ul>li>.main-nav-submenu").removeClass('main-nav-open');
			}

			jQuery('div', _this).toggleClass('main-nav-open');
		});




		// HEADER MAIN MENU
		if(window.width > 767) { //  desktop|tablet

			jQuery("#header button.nav-toggle").mouseover(function(e) {
				e.preventDefault();

				_initMainNav();

			});


		} else { // mobile

			jQuery("#header button.nav-toggle").bind("click", function(e) {
				e.preventDefault();

				_initMainNav();

			});

		}

        jQuery('body').on('click', '#header button.nav-toggle, #header nav.main-nav', function (e) {
            e.stopPropagation();
        });

        jQuery("#header button.nav-toggle, #header nav.main-nav").mouseover(function(e) {
        	 e.stopPropagation();
        });


		jQuery(document).bind("click", function() {

			_hideMainNav();

		});



		function _initMainNav() {

			// remove overlay first, no matter what
			jQuery("#main-nav-overlay").remove();
		
			// open menu
			jQuery("#header nav.main-nav").addClass('min-nav-active');

			// add overlay
			jQuery('body').append('<div id="main-nav-overlay"></div>');

			// Mobile menu open|close on click
			jQuery('#header button.nav-toggle-close').bind("click", function() {
				jQuery("#header nav.main-nav").removeClass('min-nav-active');
			});

			// Close menu on hover
	        jQuery("#main-nav-overlay, #header").mouseover(function() {

	        	_hideMainNav();

	        });

		}

		function _hideMainNav() {
			jQuery("#main-nav-overlay").remove();
			jQuery("#header nav.main-nav").removeClass('min-nav-active');
		}


		// Menu Click
		jQuery("nav.main-nav>div>ul>li a").bind("click", function(e) {
			var _href = jQuery(this).attr('href');

			if(_href == '#') {
				e.preventDefault();
			}
		});
	}


	
/** LightBox
 **************************************************************** **/
	function _lightbox() {
		var _el = jQuery(".lightbox");

		if(_el.length > 0) {


				if(typeof(jQuery.magnificPopup) == "undefined") {
					return false;
				}

				jQuery.extend(true, jQuery.magnificPopup.defaults, {
					tClose: 		'Close',
					tLoading: 		'Loading...',

					gallery: {
						tPrev: 		'Previous',
						tNext: 		'Next',
						tCounter: 	'%curr% / %total%'
					},

					image: 	{ 
						tError: 	'Image not loaded!' 
					},

					ajax: 	{ 
						tError: 	'Content not loaded!' 
					}
				});

				_el.each(function() {

					var _t 			= jQuery(this),
						options 	= _t.attr('data-plugin-options'),
						config		= {},
						defaults 	= {
							type: 				'image',
							fixedContentPos: 	false,
							fixedBgPos: 		false,
							mainClass: 			'mfp-no-margins mfp-with-zoom',
							closeOnContentClick: true,
							closeOnBgClick: 	true,
							image: {
								verticalFit: 	true
							},

							zoom: {
								enabled: 		false,
								duration: 		300
							},

							gallery: {
								enabled: false,
								navigateByImgClick: true,
								preload: 			[0,1],
								arrowMarkup: 		'<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
								tPrev: 				'Previous',
								tNext: 				'Next',
								tCounter: 			'<span class="mfp-counter">%curr% / %total%</span>'
							},
						};

					if(_t.data("plugin-options")) {
						config = jQuery.extend({}, defaults, options, _t.data("plugin-options"));
					}

					jQuery(this).magnificPopup(config);

				});

		}

	}	


/** OWL Carousel
 **************************************************************** **/
	function _owl_carousel() {


		// OWL CAROUSEL 1
		var _container = jQuery("div.owl-carousel");

		if(_container.length > 0) {

				_container.each(function() {

					var slider 		= jQuery(this);
					var options 	= slider.attr('data-plugin-options');

					// Progress Bar
					var $opt = eval('(' + options + ')');  // convert text to json

					// if($opt.progressBar == 'true') {
					//	var afterInit = progressBar;
					// } else {
					//	var afterInit = false;
					// }
					
					var afterInit = false;


					var defaults = {
						items: 					5,
						itemsCustom: 			false,
						itemsDesktop: 			[1199,4],
						itemsDesktopSmall: 		[980,3],
						itemsTablet: 			[768,2],
						itemsTabletSmall: 		false,
						itemsMobile: 			[479,1],
						singleItem: 			true,
						itemsScaleUp: 			false,

						slideSpeed: 			200,
						paginationSpeed: 		800,
						rewindSpeed: 			1000,

						autoPlay: 				false,
						stopOnHover: 			false,

						navigation: 			false,
						navigationText: [
											'<i class="fa fa-angle-left"></i>',
											'<i class="fa fa-angle-right"></i>'
										],
						rewindNav: 				true,
						scrollPerPage: 			false,

						pagination: 			true,
						paginationNumbers: 		false,

						responsive: 			true,
						responsiveRefreshRate: 	200,
						responsiveBaseWidth: 	window,

						baseClass: 				"owl-carousel",
						theme: 					"owl-theme",

						lazyLoad: 				false,
						lazyFollow: 			true,
						lazyEffect: 			"fade",

						autoHeight: 			false,

						jsonPath: 				false,
						jsonSuccess: 			false,

						dragBeforeAnimFinish: 	true,
						mouseDrag: 				true,
						touchDrag: 				true,

						transitionStyle: 		false,

						addClassActive: 		false,

						beforeUpdate: 			false,
						afterUpdate: 			false,
						beforeInit: 			false,
						afterInit: 				afterInit,
						beforeMove: 			false,
						afterMove: 				(afterInit == false) ? false : moved,
						afterAction: 			false,
						startDragging: 			false,
						afterLazyLoad: 			false
					}

					var config = jQuery.extend({}, defaults, options, slider.data("plugin-options"));
					slider.owlCarousel(config).addClass("owl-carousel-init");
					

					// Progress Bar
					var elem = jQuery(this);

					//Init progressBar where elem is $("#owl-demo")
					function progressBar(elem){
					  $elem = elem;
					  //build progress bar elements
					  buildProgressBar();
					  //start counting
					  start();
					}
				 
					//create div#progressBar and div#bar then prepend to $("#owl-demo")
					function buildProgressBar(){
					  $progressBar = jQuery("<div>",{
						id:"progressBar"
					  });
					  $bar = jQuery("<div>",{
						id:"bar"
					  });
					  $progressBar.append($bar).prependTo($elem);
					}

					function start() {
					  //reset timer
					  percentTime = 0;
					  isPause = false;
					  //run interval every 0.01 second
					  tick = setInterval(interval, 10);
					};

			 
					var time = 7; // time in seconds
					function interval() {
					  if(isPause === false){
						percentTime += 1 / time;
						$bar.css({
						   width: percentTime+"%"
						 });
						//if percentTime is equal or greater than 100
						if(percentTime >= 100){
						  //slide to next item 
						  $elem.trigger('owl.next')
						}
					  }
					}
				 
					//pause while dragging 
					function pauseOnDragging(){
					  isPause = true;
					}
				 
					//moved callback
					function moved(){
					  //clear interval
					  clearTimeout(tick);
					  //start again
					  start();
					}

				});

		}


	}




	/* 
		Mobile Check

		if( isMobile.any() ) alert('Mobile');
		if( isMobile.iOS() ) alert('iOS');
	*/

	var isMobile = {
	    iOS: function() {
	        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	    },
	    Android: function() {
	        return navigator.userAgent.match(/Android/i);
	    },
	    BlackBerry: function() {
	        return navigator.userAgent.match(/BlackBerry/i);
	    },
	    Opera: function() {
	        return navigator.userAgent.match(/Opera Mini/i);
	    },
	    Windows: function() {
	        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
	    },
	    any: function() {
	        return (isMobile.iOS() || isMobile.Android() || isMobile.BlackBerry() || isMobile.Opera() || isMobile.Windows());
	    }
	};
	
	$(document).ready(function() {

		// RADIO OPTION
		$('input[type=radio]').on('change', updateRadioOption);

		function updateRadioOption() {
			var option_value = $(this).attr('data-option-value');
			var option_base = $(this).attr('data-option-base');
			var change_image = $(this).attr('data-change-image');
			var change_model = $(this).attr('data-change-model');

			$("#item_price h4").text(option_value);
			$("#item_base span").text(option_base);
			$("#item_model").text(change_model);
			
			if (change_image != null && change_image != '' && change_image != undefined) {
				$("#item_image").attr("src", change_image);
				$("#item_zoom").attr("href", change_image);
			}
			
		}
	})	
	
	
