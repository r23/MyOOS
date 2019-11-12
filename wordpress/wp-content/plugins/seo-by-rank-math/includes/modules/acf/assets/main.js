import jQuery from 'jquery'
import App from './src/app.js'

jQuery( document ).ready( function() {
	if ( 'undefined' !== typeof RankMathApp ) {
		window.RankMathACFAnalysis = new App()
	}
} )
