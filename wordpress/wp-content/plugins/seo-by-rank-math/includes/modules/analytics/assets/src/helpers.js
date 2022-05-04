/**
 * External dependencies
 */
import { has } from 'lodash'

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'

/**
 * Get schema object for builder based on data.
 *
 * @param {string} text   Text.
 * @param {string} column Column name.
 */
export function translateText( text, column ) {
	if ( 'page_fetch_state' !== column ) {
		return text
	}

	const data = {
		PAGE_FETCH_STATE_UNSPECIFIED: __( 'Unknown fetch state', 'rank-math' ),
		SUCCESSFUL: __( 'Successful fetch', 'rank-math' ),
		SOFT_404: __( 'Soft 404', 'rank-math' ),
		BLOCKED_ROBOTS_TXT: __( 'Blocked by robots.txt', 'rank-math' ),
		NOT_FOUND: __( 'Not found (404)', 'rank-math' ),
		ACCESS_DENIED: __( 'Blocked due to unauthorized request (401)', 'rank-math' ),
		SERVER_ERROR: __( 'Server error (5xx)', 'rank-math' ),
		REDIRECT_ERROR: __( 'Redirection error', 'rank-math' ),
		ACCESS_FORBIDDEN: __( 'Blocked due to access forbidden (403)', 'rank-math' ),
		BLOCKED_4XX: __( 'Blocked due to other 4xx issue (not 403, 404)', 'rank-math' ),
		INTERNAL_CRAWL_ERROR: __( 'Internal error', 'rank-math' ),
		INVALID_URL: __( 'Invalid URL', 'rank-math' ),
	}

	return has( data, text ) ? data[ text ] : text
}

/**
 * Get schema object for builder based on data.
 *
 * @param {string} value Text.
 */
export function convertValue( value ) {
	if ( ! value ) {
		return __( 'Not available', 'rank-math' )
	}

	if ( value.includes( 'UNSPECIFIED' ) ) {
		return __( 'Unspecified', 'rank-math' )
	}

	if ( 'NEUTRAL' === value ) {
		return __( 'Excluded', 'rank-math' )
	}

	return value
}

window.rankMath = window.rankMath || {}
window.rankMath.analyticsHelpers = window.rankMath.analyticsHelpers || {}

window.rankMath.analyticsHelpers = { translateText, convertValue }
