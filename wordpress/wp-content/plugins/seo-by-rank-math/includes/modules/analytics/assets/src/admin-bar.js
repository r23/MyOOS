/**
 * External dependencies
 */
import jQuery from 'jquery'
import ContentLoader from 'react-content-loader'

/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch'
import { doAction } from '@wordpress/hooks'
import { createElement, render } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'

/**
 * Internal dependencies
 */
import Stats from './admin-bar-stats'
import { isPro } from './functions'

class AnalyticsAdminBar {
	/**
	 * Constructor.
	 */
	constructor() {
		this.init()
		this.addContentLoader()
		this.events()
	}

	/*
	 * Function to append analytics wrapper and get the data.
	*/
	init() {
		jQuery( 'body' ).prepend( this.analyticsWrapper() )
		apiFetch( {
			method: 'GET',
			path: 'rankmath/v1/an/post/' + rankMath.objectID,
		} ).then( ( response ) => {
			if ( response.errorMessage ) {
				jQuery( '#rank-math-analytics-stats-wrapper' ).remove()
				return
			}

			render(
				createElement( () => (
					<div className="rank-math-analytics-wrapper">
						<Stats data={ response } />
					</div>
				) ),
				document.getElementById( 'rank-math-analytics-stats' )
			)

			doAction( 'rank-math-analytics-stats', response )
		} ).catch( () => {
			jQuery( '#rank-math-analytics-stats-wrapper' ).remove()
		} )
	}

	/*
	 * Main Analytics stats wrapper.
	*/
	analyticsWrapper() {
		return (
			`<div id="rank-math-analytics-stats-wrapper">
				<a href="#" class="rank-math-analytics-close-stats">
					<i class="dashicons dashicons-no-alt"></i>
					<svg viewBox="0 0 462.03 462.03" xmlns="http://www.w3.org/2000/svg"><g><path d="m462 234.84-76.17 3.43 13.43 21-127 81.18-126-52.93-146.26 60.97 10.14 24.34 136.1-56.71 128.57 54 138.69-88.61 13.43 21z"></path><path d="m54.1 312.78 92.18-38.41 4.49 1.89v-54.58h-96.67zm210.9-223.57v235.05l7.26 3 89.43-57.05v-181zm-105.44 190.79 96.67 40.62v-165.19h-96.67z"></path></g></svg>
				</a>
				<div id="rank-math-analytics-stats-content">
					<div id="rank-math-analytics-stats" class="rank-math-analytics"></div>
					${ this.proContent() }
				</div>
			</div>`
		)
	}

	/*
	 * Show content loader when data is being fetched.
	*/
	addContentLoader() {
		const contentLoader = []
		for ( let i = 0; i < 4; i++ ) {
			contentLoader.push(
				<ContentLoader
					animate={ true }
					backgroundColor="#f0f2f4"
					foregroundColor="#f0f2f4"
					style={ { width: '23%', height: '83px', padding: '1rem' } }
				>
					<rect
						x="0"
						y="0"
						rx="0"
						ry="0"
						width="100%"
						height="100%"
					/>
				</ContentLoader>
			)
		}

		render(
			createElement( () => (
				<div className="rank-math-analytics-wrapper">
					{ contentLoader }
				</div>
			) ),
			document.getElementById( 'rank-math-analytics-stats' )
		)
	}

	/*
	 * Content to show when PRO plugin is not active
	*/
	proContent() {
		if ( isPro() ) {
			return ''
		}

		return (
			`<div class="rank-math-analytics-stats-footer">
			<p>
			${
			sprintf(
				// translators: KB Link
				__( 'Advanced Stats are available in the PRO version, %1$s.', 'rank-math' ),
				'<a href="https://rankmath.com/kb/analytics-stats-bar/?utm_source=Plugin&utm_medium=Analytics%20Stats%20Bar&utm_campaign=WP" target="_blank" rel="noreferrer" class="button button-primary">' + __( 'learn More', 'rank-math' ) + '</a>'
			)
			}
			</p>
			<a href="https://rankmath.com/pricing/?utm_source=Plugin&utm_medium=Analytics%20Stats%20Bar&utm_campaign=WP" target="_blank" rel="noreferrer" class="button button-primary">
				${ __( 'Upgrade to PRO', 'rank-math' ) }
			</a>
			</div>
		`
		)
	}

	/*
	 * Open and close stats click event.
	*/
	events() {
		jQuery( '.rank-math-analytics-close-stats' ).on( 'click', ( e ) => {
			e.preventDefault()
			jQuery( '#rank-math-analytics-stats-wrapper' ).toggleClass( 'hide-stats' )
			return false
		} )
	}
}

jQuery( document ).on( 'ready', () => {
	new AnalyticsAdminBar()
} )
