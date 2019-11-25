/*global RankMathApp*/
import jQuery from 'jquery'
import debounce from 'lodash/debounce'

/**
 * RankMath custom fields integration class
 */
class RankMathProductDescription {
	constructor() {
		this.excerpt = jQuery( '#excerpt' )

		if ( undefined === this.excerpt ) {
			return
		}

		this.hooks()
	}

	/**
	 * Hook into Rank Math App eco-system
	 */
	hooks() {
		wp.hooks.addFilter( 'rank_math_content', 'rank-math', this.getContent.bind( this ) )
		this.events()
	}

	/**
	 * Gather custom fields data for analysis
	 *
	 * @param {string} content Content
	 *
	 * @return {string} New content
	 */
	getContent( content ) {
		content += ( 'undefined' !== typeof tinymce && tinymce.activeEditor && 'excerpt' === tinymce.activeEditor.id ) ? tinymce.activeEditor.getContent() : this.excerpt.val()
		return content
	}

	/**
	 * Capture events from custom fields to refresh Rank Math analysis
	 */
	events() {
		if ( 'undefined' !== typeof tinymce && tinymce.activeEditor && 'undefined' !== typeof tinymce.editors.excerpt ) {
			tinyMCE.editors.excerpt.on( 'keyup change', debounce( () => {
				RankMathApp.refresh( 'content' )
			}, 500 ) )
		}
	}
}

jQuery( window ).on( 'load', () => {
	new RankMathProductDescription()
} )
