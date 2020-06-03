(function ($) {
	// Calculates necessary things on resize
	$(window).on('resize',function() {
		var navbarMainHeight = cpSchoolThemeHelpers.getNavbarHeight();
		cpSchoolThemeHelpers.setCSSVar(`--header-main-height`, navbarMainHeight+'px');

		var headerDropboxGap = cpSchoolThemeHelpers.getHeaderDropboxGap(false);
		cpSchoolThemeHelpers.setCSSVar(`--header-main-gap-height`, headerDropboxGap+'px');

		var headerButtonsMenuWidth = cpSchoolThemeHelpers.getHeaderButtonsMenuInlineWidth();
		if(headerButtonsMenuWidth) {
			cpSchoolThemeHelpers.setCSSVar(`--header-main-buttons-menu-width`, headerButtonsMenuWidth+'px');
		}

		var navbarMainBrandHolderHeight = cpSchoolThemeHelpers.getNavbarBrandHolderHeight();
		if(navbarMainBrandHolderHeight) {
			cpSchoolThemeHelpers.setCSSVar(`--header-main-navbar-brand-holder-height`, navbarMainBrandHolderHeight+'px');
		}

		/* Disabled - parellax script needs to be changed.
		if(cpSchoolData.parallaxHeader) {
			cpSchoolThemeHelpers.manageParallaxHeader(false);
		}
		*/

		cpSchoolThemeHelpers.setSidebarStickness();
	});
	// Lets trigger resize action when page is ready.
	$(document).ready(function() {
		$(window).trigger('resize');
	});

	// Support for transitions in dropdown.
	$('.dropdown').on('shown.bs.dropdown', function (action) {
		var droprown = $(this);
		setTimeout(function(){
			droprown.addClass('shown');
		}, 10);
	});
	$('.dropdown-menu').on('transitionend', function (e) {
		if($(e.target).hasClass('dropdown-menu')) {
			if(!$(this).hasClass('show')) {
				$(this).parent().removeClass('shown');
			}
		}
	});

	// Add sticky class to nav.
	if ('IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function(entries) {
			if( $('body').hasClass( 'navbar-main-sticky-top' ) ) {
				if (entries[0].intersectionRatio === 0) {
					$("#wrapper-navbar-main-top").addClass('intersected');
					$('#wrapper-navbar-main').addClass('navbar-sticks');
				}
				else if (entries[0].intersectionRatio === 1) {
					$("#wrapper-navbar-main-top").removeClass('intersected');
					$('#wrapper-navbar-main').removeClass('navbar-sticks');
				}
			}
		}, { threshold: [0, 1] });
		observer.observe(document.querySelector("#wrapper-navbar-main-top"));
	}

	// Handle dismissal of alerts.
	var alertBar = $('#site-alert');
	if(alertBar.length) {
		var alert_ver = alertBar.data('ver');

		alertBar.find('[data-dismiss="alert"]').click(function (e) {
			Cookies.set('site_alert_bar_dismiss_ver', alert_ver, {expires: 365});
		});
	}

	// Focus search input after opening search modal.
	$('#modal-search').on('shown.bs.modal', function () {
		$('#modal-search').find('input').first().trigger('focus');
	});

	// Show alert popup if its configured.
	var alertPopup = $('#modal-alert');
	if(alertPopup.length) {
		alertPopup.modal('show');
		alertPopup.on('hidden.bs.modal', function (e) {
			var alert_ver = alertPopup.data('ver');
			Cookies.set('site_alert_popup_dismiss_ver', alert_ver, {expires: 365});
		});
	}

	// Handles basic animations on the site.
	if(cpSchoolData.animations) {
		$('.entry-content > .alignfull, .entry-content > .alignwide, .entry-content > .aligncenter').attr('data-aos', 'fade-up');
		$('.entry-content > .alignleft').attr('data-aos', 'fade-right');
		$('.entry-content > .alignright').attr('data-aos', 'fade-left');

		AOS.init({
			offset: 150,
			delay: 50,
			duration: 800,
			once: true,
			disable: function() {
				if(window.document.documentMode <= 11) {
					return true
				}
				else {
					return false;
				}
			}
		});
	}

	// This will make vars in css work in IE11.
	if (typeof cssVars === "function") {
		cssVars({
			preserveStatic: false
		});
	}
})(jQuery);

var cpSchoolThemeHelpers = (function ($) {
	var methods = {};

	/* Disabled - parellax script needs to be changed.
	methods.manageParallaxHeader = function (destroy) {
		if(typeof cpSchoolData.parallaxHeader !== 'object' && !destroy) {
			var headerImage = document.querySelectorAll('.hero-image-holder img');
			cpSchoolData.parallaxHeader = new simpleParallax(headerImage, {
				delay: 2,
				orientation: 'up',
				scale: 1.3,
			});
		}
		if(cpSchoolData.parallaxHeader && destroy) {
			cpSchoolData.parallaxHeader.destroy();
			cpSchoolData.parallaxHeader = false;
		}
	};
	*/

	methods.setCSSVar = function (varName, value) {
		if(getComputedStyle(document.documentElement).getPropertyValue(varName) != value) {
			document.documentElement.style.setProperty(varName, value);
		}
	};

	methods.getNavbarHeight = function () {
		var navbarMain = $('#navbar-main');

		if(navbarMain.length) {
			return navbarMain.outerHeight();
		} 

		return 0;
	};

	methods.getNavbarBrandHolderHeight = function () {
		var navbarBrandHolder = $('#navbar-main .navbar-brand-holder');

		if(navbarBrandHolder.length) {
			return navbarBrandHolder.outerHeight();
		} 

		return 0;
	};

	methods.getHeaderDropboxGap = function (strict) {
		var navbarMainHeight = methods.getNavbarHeight();
		var navbarMainBrand = $('#wrapper-navbar-main.navbar-style-dropbox .navbar-brand');

		if(navbarMainHeight && navbarMainBrand.length) {
			var difference = navbarMainBrand.outerHeight() - navbarMainHeight;
			if(strict) {
				if(difference > 0) {
					return difference;
				}
			}
			else {
				var margin = 3*16;
				if(difference > margin) {
					return difference - margin;
				}
			}
		}

		return 0;
	};

	methods.getHeaderButtonsMenuInlineWidth = function () {
		var navbarMainButtonsMenu = $('#navbar-main-nav-buttons');

		if(navbarMainButtonsMenu.length) {
			var navbarMainContainerWidth = $('#navbar-main .navbar-container').width();
			var navbarMainNavWidth = $('#menu-main-desktop').width();

			var navbarMainButtonsMenuOuterWidth = navbarMainButtonsMenu.outerWidth();
			if(navbarMainContainerWidth > (navbarMainButtonsMenuOuterWidth*2 + navbarMainNavWidth)){
				return navbarMainButtonsMenuOuterWidth;
			}
		}

		return 0;
	};

	methods.setSidebarStickness = function () {
		//TODO we should check that with js var
		if($('body').hasClass('sidebars-check-sticky')) {
			$( ".sidebar-widget-area-content" ).each(function( index ) {
				if( $(window).height() > ( $(this).outerHeight() + parseInt( $(this).css('top') ) ) ) {
					$(this).addClass('sidebar-sticky');
				}
				else {
					$(this).removeClass('sidebar-sticky');
				}
			});
		}
	};

	return methods;
})(jQuery);