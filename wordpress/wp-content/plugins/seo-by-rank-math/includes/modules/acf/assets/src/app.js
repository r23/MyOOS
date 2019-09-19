var collect = require( './collect.js' )

var analysisTimeout = 0

var App = function() {
	RankMathApp.registerPlugin( rankMath.acf.pluginName )
	wp.hooks.addFilter( 'rank_math_content', rankMath.acf.pluginName, collect.append.bind( collect ) )

	if( rankMath.acf.enableReload ) {
		this.events()
	}
}

App.prototype.events = function() {
	var self = this
	jQuery( '.acf-field' ).on( 'change', function() {
		self.maybeRefresh()
	})
}

App.prototype.maybeRefresh = function() {
	if ( analysisTimeout ) {
		window.clearTimeout( analysisTimeout )
	}

	analysisTimeout = window.setTimeout( function() {
		RankMathApp.reloadPlugin( rankMath.acf.pluginName )
	}, rankMath.acf.refreshRate )
}

module.exports = App
