/*global RankMathApp*/
import $ from 'jquery'
import collect from './collect'
import { addFilter } from '@wordpress/hooks'

class App {
	analysisTimeout = 0

	constructor() {
		addFilter( 'rank_math_content', 'rank-math', collect.append.bind( collect ) )
		if ( rankMath.acf.enableReload ) {
			this.events()
		}
	}

	events() {
		$( '.acf-field' ).on( 'change', () => {
			this.maybeRefresh()
		} )
	}

	maybeRefresh() {
		if ( this.analysisTimeout ) {
			clearTimeout( this.analysisTimeout )
		}

		this.analysisTimeout = setTimeout( function() {
			RankMathApp.refresh( 'content' )
		}, rankMath.acf.refreshRate )
	}
}

export default App
