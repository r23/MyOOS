/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $, api, _ ) {
	//Set and pass to customizer header related dimensions so they can be used with CSS.
	$('.navbar-brand').on('transitionend', function (e) {
		if(!$('#wrapper-navbar-main').hasClass('navbar-sticks')) {
			var navbarMainHeight = cpSchoolThemeHelpers.getNavbarHeight();
			var headerDropboxGap = cpSchoolThemeHelpers.getHeaderDropboxGap(false);

			cpSchoolThemeHelpers.setCSSVar(`--header-main-height`, navbarMainHeight+'px');
			cpSchoolThemeHelpers.setCSSVar(`--header-main-gap-height`, headerDropboxGap+'px');

			// Lets pass the data only when minimum screen size is meet.
			if($(window).width() >= 1300) {
				wp.customize.preview.send( 'set', {
					'header_main_height': navbarMainHeight, 
					'header_main_gap_height': headerDropboxGap 
				} );
			}
		}
	});

	// Enable or disable parallax based on Customizer setting.
	/* Disabled - parellax script needs to be changed.
	wp.customize( 'hero_main_parallax', function( value ) {
		value.bind( function( to ) {
			if(to == true) {
				cpSchoolThemeHelpers.manageParallaxHeader(false);
			}
			else if(to == false) {
				cpSchoolThemeHelpers.manageParallaxHeader(true);
			}
		} );
	} );
	*/

	// Check for sidebar stickness.
	wp.customize( 'sidebars_sticky', function( value ) {
		value.bind( function( to ) {
			cpSchoolThemeHelpers.setSidebarStickness();
		} );
	} );
	$(document).ready(function() {
		if ( 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh ) {
			wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {
				cpSchoolThemeHelpers.setSidebarStickness();
			} );
		}
	});
	
	
}( jQuery, wp.customize, _ ) );
