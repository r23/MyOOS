/*!
* Rank Math - SEO Analysis
*
* @version 0.9.0
* @author  Rank Math
*/
;( function( $ ) {

	'use strict'

	$( function() {

		var RankMathSeoAnalysis = {
			init: function() {
				this.wrap     = $( '.rank-math-seo-analysis-wrap' )
				this.results  = this.wrap.find( '.rank-math-results-wrapper' )
				this.progress = this.wrap.find( '.progress' )
				this.counter  = this.wrap.find( '.progress-bar span' )

				this.events()
				this.graphs()
				this.single()
			},

			events: function() {
				var self = this

				this.wrap.on( 'click', '.rank-math-recheck', function( event ) {
					event.preventDefault()

					self.wrap.addClass( 'is-loading' ).removeClass( 'is-loaded' )
					self.results.empty()

					$( this ).hide()

					var fadeOut = function( notice ) {
						self.wrap.find( '> h2' ).after( notice )
						notice.slideDown()
						setTimeout( function() {
							notice.slideUp( function() {
								notice.remove()
							})
						}, 3000 )
					}

					$.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'rank_math_analyze',
							security: rankMath.security,
							u: self.wrap.find( '.rank-math-analyze-url' ).val()
						},
						beforeSend: function() {
							self.renderProgressBar()
						},
						complete: function() {
							clearInterval( self.interval )
							self.progress.css( 'width', '100%' )
							self.counter.html( '100%' )
						},
						error: function() {
							var notice = $( '<div class="notice notice-error is-dismissible"><p>An error occured.</p></div>' ).hide()
							fadeOut( notice )
							self.wrap.addClass( 'is-loaded' ).removeClass( 'is-loading' )
						},
						success: function( results ) {
							self.results.html( results )
							var notice = self.results.find( '.notice' )
							self.wrap.addClass( 'is-loaded' ).removeClass( 'is-loading' )
							self.graphs()
							fadeOut( notice )
						}
					})
				})

				self.wrap.on( 'click', '.result-action', function( event ) {
					event.preventDefault()
					$( this ).parent( 'div' ).toggleClass( 'expanded' )
				})
			},

			renderProgressBar: function() {
				var self = this,
					width = 0

				self.progress.css( 'width', width )
				self.interval = setInterval( function() {
					width++

					if ( 100 === width ) {
						clearInterval( self.interval )
					}

					self.counter.html( width + '%' )
					self.progress.css( 'width', width + '%' )
				}, 30 )
			},

			graphs: function() {

				var circle = $( '#rank-math-circle-progress' )

				if ( 0 > circle.length ) {
					return
				}

				var val = circle.data( 'result' ),
					resultcolors = [ '#58bb58', '#58bb58' ] // greens

				if ( 0.5 > val ) {
					resultcolors = [ '#ed6a5e', '#ed6a5e' ] // red
				} else if ( 0.7 > val ) {
					resultcolors = [ '#f7ca63', '#f7ca63' ] // yellow
				}

				circle.circleProgress({
					value: val,
					size: 207,
					thickness: 17,
					lineCap: 'round',
					emptyFill: '#e9e9ea',
					fill: { gradient: resultcolors }
				})
			},

			single: function() {

				var self    = this,
					current = self.wrap.find( '.rank-math-current-url' ),
					url     = self.wrap.find( '.rank-math-analyze-url' ),
					recheck = self.wrap.find( '.rank-math-recheck' ),
					change  = self.wrap.find( '.rank-math-changeurl' ),
					ok      = self.wrap.find( '.rank-math-changeurl-ok' )

				if ( ! url.length ) {
					return self
				}

				change.on( 'click', function() {

					// Hide
					current.hide()
					change.hide()

					// Show
					url.show()
					ok.show()

					return false
				})

				ok.on( 'click', function() {

					// Hide
					url.hide()
					ok.hide()

					// Show
					current.show()
					change.show()

					// Change url
					current.html( url.val() )

					recheck.trigger( 'click' )

					return false
				})

				url.on( 'keypress', function( event ) {
					if ( 13 === event.keyCode ) {
						ok.trigger( 'click' )
					}
				})

				// Auto-run single page analysis
				recheck.trigger( 'click' )
			}
		}

		RankMathSeoAnalysis.init()
	})

}( jQuery ) )
