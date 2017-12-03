
	window.width 	= jQuery(window).width();
	window.height 	= jQuery(window).height();

	/* Init */
	jQuery(window).ready(function () {


		// jQuery 3.x do no support size() - should be replaceced with .legth
		// We use this hack to make old plugins working
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
		

			_topNav();

		}


		/** Bootstrap Tooltip **/ 
		jQuery('[data-toggle="tooltip"]').tooltip({
			placement: $(this).data('placement'),
			html: true
		});

	}



	


/** 01. Top Nav
 **************************************************************** **/
	function _topNav() {
		window.scrollTop 		= 0;
		window._cmScroll 		= 0;
		var _header_el 			= jQuery("#header");

		jQuery(window).scroll(function() {
			_toTop();
		});

		/* Scroll To Top */
		function _toTop() {
			_scrollTop = jQuery(document).scrollTop();
			
			if(_scrollTop > 100) {

				if(jQuery("#toTop").is(":hidden")) {
					jQuery("#toTop").show();
				}

			} else {

				if(jQuery("#toTop").is(":visible")) {
					jQuery("#toTop").hide();
				}

			}

		}


		// Mobile Submenu
		var addActiveClass 	= false;
		jQuery("#topMain a.dropdown-toggle").bind("click", function(e) {
			
			if(jQuery(this).attr('href') == "#") {
				e.preventDefault();
			}

			addActiveClass = jQuery(this).parent().hasClass("resp-active");
			jQuery("#topMain").find(".resp-active").removeClass("resp-active");

			if(!addActiveClass) {
				jQuery(this).parents("li").addClass("resp-active");
			}

			return;

		});


		// Srearch
		jQuery('li.search i.fa').click(function () {
			if(jQuery('#header .search-box').is(":visible")) {
				jQuery('#header .search-box').fadeOut(300);
			} else {
				jQuery('.search-box').fadeIn(300);
				jQuery('#header .search-box form input').focus();

				// hide quick cart if visible
				if (jQuery('#header li.quick-cart div.quick-cart-box').is(":visible")) {
					jQuery('#header li.quick-cart div.quick-cart-box').fadeOut(300);
				}
			}
		}); 

		// close search box on body click
		if(jQuery('#header li.search i.fa').size() != 0) {
			jQuery('#header .search-box, #header li.search i.fa').on('click', function(e){
				e.stopPropagation();
			});

			jQuery('body').on('click', function() {
				if(jQuery('#header li.search .search-box').is(":visible")) {
					jQuery('#header .search-box').fadeOut(300);
				}
			});
		}

		jQuery(document).bind("click", function() {
			if(jQuery('#header li.search .search-box').is(":visible")) {
				jQuery('#header .search-box').fadeOut(300);
			}
		});


		// Close Fullscreen Search
		jQuery("#closeSearch").bind("click", function(e) {
			e.preventDefault();

			jQuery('#header .search-box').fadeOut(300);
		});



		// Page Menu [mobile]
		jQuery("button#page-menu-mobile").bind("click", function() {
			jQuery(this).next('ul').slideToggle(150);
		});


		// Quick Cart
		jQuery('li.quick-cart>a').click(function (e) {
			e.preventDefault();
			
			var _quick_cart_box = jQuery('li.quick-cart div.quick-cart-box');

			if(_quick_cart_box.is(":visible")) {
				_quick_cart_box.fadeOut(300);
			} else {
				_quick_cart_box.fadeIn(300);

				// close search if visible
				if(jQuery('li.search .search-box').is(":visible")) {
					jQuery('.search-box').fadeOut(300);
				}
			}
		});
		// close quick cart on body click
		if(jQuery('li.quick-cart>a').size() != 0) {
			jQuery('li.quick-cart').on('click', function(e){
				e.stopPropagation();
			});

			jQuery('body').on('click', function() {
				if (jQuery('li.quick-cart div.quick-cart-box').is(":visible")) {
					jQuery('li.quick-cart div.quick-cart-box').fadeOut(300);
				}
			});
		}



		// MOBILE TOGGLE BUTTON
		window.currentScroll = 0;
		jQuery("button.btn-mobile").bind("click", function(e) {
			e.preventDefault();

			jQuery(this).toggleClass('btn-mobile-active');
			jQuery('html').removeClass('noscroll');
			jQuery('#menu-overlay').remove();
			jQuery("#topNav div.nav-main-collapse").hide(0);

			if(jQuery(this).hasClass('btn-mobile-active')) {
				jQuery("#topNav div.nav-main-collapse").show(0);
				jQuery('html').addClass('noscroll');
				jQuery('body').append('<div id="menu-overlay"></div>');
				 window.currentScroll = jQuery(window).scrollTop();
			} else {
				 jQuery('html,body').animate({scrollTop: currentScroll}, 300, 'easeInOutExpo');
			}
		});




		// BOTTOM NAV
		if(_header_el.hasClass('bottom')) {

			// Add dropup class
			_header_el.addClass('dropup');
			window.homeHeight 	= jQuery(window).outerHeight() - 55;
		

			// sticky header
			if(_header_el.hasClass('sticky')) {
				window.isOnTop 		= true;


				// if scroll is > 60%, remove class dropup
				jQuery(window).scroll(function() {
					if(jQuery(document).scrollTop() > window.homeHeight / 2) {
						_header_el.removeClass('dropup');
					} else {
						_header_el.addClass('dropup');
					}
				});


				// Add fixed|not fixed & dropup|no dropup
				jQuery(window).scroll(function() {
					if(jQuery(document).scrollTop() > window.homeHeight) {
						if(window.isOnTop === true) {
							jQuery('#header').addClass('fixed');
							_header_el.removeClass('dropup');
							window.isOnTop = false;
						}
					} else {
						if(window.isOnTop === false) {
							jQuery('#header').removeClass('fixed');
							_header_el.addClass('dropup');
							window.isOnTop = true;
						}
					}
				});

				// get window height on resize
				jQuery(window).resize(function() {
					window.homeHeight = jQuery(window).outerHeight();
				});

			}

		} else

		// STICKY
		if(_header_el.hasClass('sticky')) {

			_topBar_H 	= jQuery("#topBar").outerHeight() || 0;

			// Force fixed header on mobile to avoid "jump" effect.
			if(window.width <= 992 && _topBar_H < 1) {

				var _scrollTop 	= jQuery(document).scrollTop();
					_header_H 	= _header_el.outerHeight() || 0;

					_header_el.addClass('fixed');
					jQuery('body').css({"padding-top":_header_H+"px"});

			}


		} else 


		// STATIC + TRANSPARENT
		if(_header_el.hasClass('static') && _header_el.hasClass('transparent')) {

			_topBar_H 	= jQuery("#topBar").outerHeight() || 0;

			// Force fixed header on mobile to avoid "jump" effect.
			if(window.width <= 992 && _topBar_H < 1) {

				var _scrollTop 	= jQuery(document).scrollTop();
					_header_H 	= _header_el.outerHeight() || 0;

					_header_el.addClass('fixed');

			}



			jQuery(window).scroll(function() {

				if((window.width > 992 && _topBar_H < 1) || _topBar_H > 0) { // 992 to disable on mobile

					var _scrollTop 	= jQuery(document).scrollTop();

					if(_scrollTop > _topBar_H) {
						_header_el.addClass('fixed');

						_header_H = _header_el.outerHeight() || 0;

					} else {


						_header_el.removeClass('fixed');
					}

				}

			});



		} else
		
		if(_header_el.hasClass('static')) {
			// _header_H = _header_el.outerHeight() + "px";
			// jQuery('body').css({"padding-top":_header_H});
		}





		/** OVERLAY MENU
		 *************************** **/
		if(jQuery("#menu_overlay_open").length > 0) {
			var is_ie9 = jQuery('html').hasClass('ie9') ? true : false;

			if(is_ie9 == true) {
				jQuery("#topMain").hide();
			}

			// open
			jQuery("#menu_overlay_open").bind("click", function(e) {
				e.preventDefault();
				
				jQuery('body').addClass('show-menu');

				if(is_ie9 == true) {
					jQuery("#topMain").show();
				}

			});

			// close
			jQuery("#menu_overlay_close").bind("click", function(e) {
				e.preventDefault();

				if(jQuery('body').hasClass('show-menu')) {
					jQuery('body').removeClass('show-menu');
				}

				if(is_ie9 == true) {
					jQuery("#topMain").hide();
				}

			});

			// 'esc' key
			jQuery(document).keyup(function(e) {
				if(e.keyCode == 27) {
					if(jQuery('body').hasClass('show-menu')) {
						jQuery('body').removeClass('show-menu');
					}

					if(is_ie9 == true) {
						jQuery("#topMain").hide();
					}
				}
			});

		}


		// quick cart & search for mobile - top calculate
		// Quick Cart & top Search Fix (if #topBar exists).
		if(jQuery("#topBar").length > 0) {
			jQuery("#topNav ul").addClass('has-topBar');
		}
		
		// Hide Cart & Search on Scroll
		jQuery(window).scroll(function() {
			if(window.width < 769) {
				// hide quick cart if visible
				if (jQuery('#header li.quick-cart div.quick-cart-box').is(":visible")) {
					jQuery('#header li.quick-cart div.quick-cart-box').fadeOut(0);
				}
				// hide search if visible
				if(jQuery('#header li.search .search-box').is(":visible")) {
					jQuery('#header .search-box').fadeOut(0);
				}
			}
		});
		

		// quantity
		jQuery('.incr-btn').click(function (e) {
			var $button = $(this);
			var newVal = 0;
			var oldValue = $button.parent().find('.quantity').val();
			$button.parent().find('.incr-btn[data-action="decrease"]').removeClass('inactive');
			if ($button.data('action') === 'increase') {
				newVal = parseFloat(oldValue) + 1;
			} else {
				// Don't allow decrementing below 1
				if (oldValue > 1) {
					newVal = parseFloat(oldValue) - 1;
				} else {
					newVal = 1;
					$button.addClass('inactive');
				}
			}
			$button.parent().find('.quantity').val(newVal);
			e.preventDefault();
		});		
		
	
		
	}


