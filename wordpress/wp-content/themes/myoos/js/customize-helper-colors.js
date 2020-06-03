/**
 * Helper needed to calculate accessible colors. 
 * Heavily based on Twenty Twenty theme.
 */

/**
 * Color Calculations.
 *
 * @param {string} backgroundColor - The background color.
 * @param {number} accentHue - The hue for our accent color.
 *
 * @return {Object} - this
 */
function _cpSchoolColor( backgroundColor, accentHue ) {
	// Set the object properties.
	this.backgroundColor = backgroundColor;
	
	this.accentColorObj = new Color( accentHue );
	this.accentColorObjHSL = this.accentColorObj.toHsl();
	
	this.accentHue = this.accentColorObjHSL.h;
	this.accentSat = this.accentColorObjHSL.s;
	this.bgColorObj = new Color( backgroundColor );
	this.textColorObj = this.bgColorObj.getMaxContrastColor();
	this.textColor = this.textColorObj.toCSS();
	this.isDark = 0.5 > this.bgColorObj.toLuminosity();
	this.isLight = ! this.isDark;

	// Return the object.
	return this;
}

/**
 * Used with strict mode only. 
 * It forces hight contrast between accent and text.
 * 
 * Builds an array of Color objects based on the accent hue.
 * For improved performance we only build half the array
 * depending on dark/light background-color.
 *
 * @return {Object} - this
 */
_cpSchoolColor.prototype.setAccentColorsArray = function() {
	
	var self = this,
		minSaturation = 10,
		maxSaturation = self.accentSat,
		minLightness = 10,
		maxLighness = 85,
		stepSaturation = 2,
		stepLightness = 2,
		pushColor = function() {
			var colorObj = new Color( {
					h: self.accentHue,
					s: s,
					l: l
				} ),
				item,
				/**
				 * Get a score for this color in contrast to its background color and surrounding text.
				 *
				 * @since 1.0.0
				 * @param {number} contrastBackground - WCAG contrast with the background color.
				 * @param {number} contrastSurroundingText - WCAG contrast with surrounding text.
				 * @return {number} - 0 is best, higher numbers have bigger difference with the desired scores.
				 */
				getScore = function( contrastBackground, contrastSurroundingText ) {
					var diffBackground = ( 7 >= contrastBackground ) ? 0 : 7 - contrastBackground,
						diffSurroundingText = ( 3 >= contrastSurroundingText ) ? 0 : 3 - contrastSurroundingText;

					return diffBackground + diffSurroundingText;
				};

			item = {
				color: colorObj,
				contrastBackground: colorObj.getDistanceLuminosityFrom( self.bgColorObj ),
				contrastText: colorObj.getDistanceLuminosityFrom( self.textColorObj )
			};

			// Check a minimum of 4.5:1 contrast with the background and 3:1 with surrounding text.
			if ( 4.5 > item.contrastBackground || 3 > item.contrastText ) {
				return;
			}

			// Get a score for this color by multiplying the 2 contrasts.
			// We'll use that to sort the array.
			item.score = getScore( item.contrastBackground, item.contrastText );

			self.accentColorsArray.push( item );
		},
		s, l, aaa;

	this.accentColorsArray = [];

	// We're using `for` loops here because they perform marginally better than other loops.
	for ( s = minSaturation; s <= maxSaturation; s += stepSaturation ) {
		for ( l = minLightness; l <= maxLighness; l += stepLightness ) {
			pushColor( s, l );
		}
	}

	// Check if we have colors that are AAA compliant.
	aaa = this.accentColorsArray.filter( function( color ) {
		return 7 <= color.contrastBackground;
	} );

	// If we have AAA-compliant colors, alpways prefer them.
	if ( aaa.length ) {
		this.accentColorsArray = aaa;
	}

	// Sort colors by contrast.
	this.accentColorsArray.sort( function( a, b ) {
		return a.score - b.score;
	} );
	return this;
};

/**
 * Get accessible text-color.
 *
 * @since 1.0.0
 *
 * @return {Color} - Returns a Color object.
 */
_cpSchoolColor.prototype.getTextColor = function() {
	return this.textColor;
};

/**
 * Get accessible color for the defined accent-hue and background-color.
 *
 * @since 1.0.0
 *
 * @return {Color} - Returns a Color object.
 */
_cpSchoolColor.prototype.getAccentColor = function() {
	return this.accentColorObj.getReadableContrastingColor( this.bgColorObj, 6 );
};

/**
 * Get accessible color for the defined accent-hue and background-color.
 *
 * @since 1.0.0
 *
 * @return {Color} - Returns a Color object.
 */
_cpSchoolColor.prototype.getAccentColorHl = function() {
	return this.accentColorObj.getReadableContrastingColor( this.bgColorObj, 8 );
};

/**
 * Get strict accessible color for the defined accent-hue and background-color.
 * Strict means that contrast between accent and text is proper.
 * This mode is currently not used.
 *
 * @since 1.0.0
 *
 * @return {Color} - Returns a Color object.
 */
_cpSchoolColor.prototype.getAccentColorScrict = function() {
	this.setAccentColorsArray();

    var fallback;
    
    var array_index = 0;

	// If we have colors returns the 1st one - it has the highest score.
	if ( this.accentColorsArray[array_index] ) {
		return this.accentColorsArray[array_index].color;
	}

	// Fallback.
	fallback = new Color( 'hsl(' + this.accentHue + ',75%,50%)' );
	return fallback.getReadableContrastingColor( this.bgColorObj, 4.5 );
};

/**
 * Return a new instance of the _cpSchoolColor object.
 *
 * @since 1.0.0
 * @param {string} backgroundColor - The background color.
 * @param {number} accentHue - The hue for our accent color.
 * @return {Object} - this
 */
function cpSchoolColor( backgroundColor, accentHue, accentSat ) {// jshint ignore:line
	var color = new _cpSchoolColor( backgroundColor, accentHue, accentSat );
	return color;
}