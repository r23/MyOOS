/**
 * Additional tweaks needed for customizer to reflect changes.
 *
 * @since 1.0.0
 */

( function( $ ) {
	// Wait until the customizer has finished loading.
	wp.customize.bind( 'ready', function() {

		// Calculate colors for elements that have customizable BG.
		var customBGSettingsNames = ['color_bg', 'color_bg_alt', 'color_boxes', 'header_main_bg_color', 'header_secondary_bg_color', 'hero_main_bg_color', 'footer_main_bg_color', 'alert_bg_color'];
		var customBGAccentSettingsNames = ['color_accent_source', 'color_accent_hl_source', 'color_bg_alt_accent_source'];

		customBGAccentSettingsNames.forEach( function( setting ) {
			wp.customize( setting, function( value ) {
				value.bind( function( to ) {
					customBGSettingsNames.forEach( function( setting ) {
						setCustomBGColors(setting);
					} );
				} );
			} );
		} );

		customBGSettingsNames.forEach( function( setting ) {
			wp.customize( setting, function( value ) {
				value.bind( function( to ) {
					setCustomBGColors(setting);
				} );
			} );
		} );

		// Set things related to custom header bg.
		wp.customize( 'hero_main_header_main_opacity', function( value ) {
			value.bind( function( to ) {
				if(to < 100) {
					wp.customize( 'hero_main_header_main_bg_transparent' ).set( true );
				}
				else {
					wp.customize( 'hero_main_header_main_bg_transparent' ).set( false );
				}
			} );
		} );
		wp.customize( 'hero_main_style', function( value ) {
			value.bind( function( to ) {
				if(to == 'disabled') {
					wp.customize( 'hero_main_header_main_bg_transparent' ).set( false );
				}
			} );
		} );

		// Set settings based on values sent by previewer script.
		wp.customize.previewer.bind( 'set', function( data ) {
			$.each( data, function( name, value ) {
				wp.customize( name ).set( value );
			} );
		 } );
	} );

	// Set all colors in single color pallete.
	function setCustomBGColors(optionName) {
		var contrast, accent, accentA, accentContrast, accentHl, accentHlA, accentHlContrast;
		var BGColor = wp.customize( optionName ).get();

		if(BGColor) {
			var colorAccent = false;

			var customAccent = wp.customize( optionName+'_accent_source' );
			if(customAccent) {
				colorAccent = customAccent.get();
			}
			

			if(!colorAccent) {
				var alt_accent_options = ['header_main_bg_color', 'hero_main_bg_color', 'footer_main_bg_color'];
				if(jQuery.inArray( optionName, alt_accent_options ) !== -1) {
					colorAccent = wp.customize( 'color_bg_alt_accent_source' ).get();
				}

				if(!colorAccent) {
					colorAccent = wp.customize( 'color_accent_source' ).get();
				}
			}

			//lets use accent color when HL color is not set
			var colorAccentHl = wp.customize( 'color_accent_hl_source' ).get();
			if(!colorAccentHl) {
				colorAccentHl = colorAccent;
			}

			var BGColors = cpSchoolColor( BGColor, colorAccent );
			var BGColorsHl = cpSchoolColor( BGColor, colorAccentHl );

			contrast = BGColors.getTextColor();

			accent = BGColors.getAccentColor().toCSS();
			accentA = BGColors.getAccentColor().a(0.5).toCSS();
			accentContrast = getContrastColor(accent).toCSS();

			accentHl = BGColorsHl.getAccentColor().toCSS();
			accentHlA = BGColorsHl.getAccentColor().a(0.5).toCSS();
			accentHlContrast = getContrastColor(accentHl).toCSS();
		}
		else {
			contrast = accent = accentA = accentContrast = accentHl = accentHlA = accentHlContrast = false;
		}

		wp.customize( optionName+'_contrast' ).set( contrast );
		wp.customize( optionName+'_accent' ).set( accent );
		wp.customize( optionName+'_accent_a' ).set( accentA );
		wp.customize( optionName+'_accent_contrast' ).set( accentContrast );
		wp.customize( optionName+'_accent_hl' ).set( accentHl );
		wp.customize( optionName+'_accent_hl_a' ).set( accentHlA );
		wp.customize( optionName+'_accent_hl_contrast' ).set( accentHlContrast );
	}

	// Get maximum contrast for color
	function getContrastColor(color) {
		var colorObj = new Color( color );

		return colorObj.getMaxContrastColor();
	}
}( jQuery ) );
