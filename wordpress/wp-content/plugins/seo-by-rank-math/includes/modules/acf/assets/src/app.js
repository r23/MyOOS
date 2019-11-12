/*global RankMathApp*/
import $ from 'jquery'
import collect from './collect'
import { addFilter } from '@wordpress/hooks'

class App {
	analysisTimeout = 0

	constructor() {
		RankMathApp.registerPlugin( rankMath.acf.pluginName )
		addFilter( 'rank_math_content', rankMath.acf.pluginName, collect.append.bind( collect ) )

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
			RankMathApp.reloadPlugin( rankMath.acf.pluginName )
		}, rankMath.acf.refreshRate )
	}
}

export default App
