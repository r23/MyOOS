( function( $ ) {

	// ready event
	$( function() {
		// listen for the reload
		document.addEventListener( 'reload.hu', function( e ) {
			var customOptions = { config: { dontSellLink: true, privacyPolicyLink: true } };
			
			// set widget options
			hu.setOptions( customOptions );
		} );
		
		// listen for the display
		document.addEventListener( 'display.hu', function( e ) {
			var val = [],
				container = $( '#hu' ),
				customOptions = { config: {} };
			
			$( parent.document ).find( 'input[name="cn_laws"]:checked' ).each( function() {
				val.push( $( this ).val() );
			} );
							
			if ( $.inArray( 'ccpa', val ) !== -1 ) {
				$( container ).find( '#hu-notice-dontsell-container' ).css( 'display', 'block' );

				$.extend( customOptions.config, { dontSellLink: true } );
			} else {
				$( container ).find( '#hu-notice-dontsell-container' ).css( 'display', 'none' );

				$.extend( customOptions.config, { dontSellLink: false } );
			}
			if ( $.inArray( 'gdpr', val ) !== -1 ) {
				$( container ).find( '#hu-notice-privacy-container' ).css( 'display', 'block' );

				$.extend( customOptions.config, { privacyPolicyLink: true } );
			} else {
				$( container ).find( '#hu-notice-privacy-container' ).css( 'display', 'none' );

				$.extend( customOptions.config, { privacyPolicyLink: false } );
			}
			
			// console.log( customOptions );
			
			// set widget options
			hu.setOptions( customOptions );

			// console.log( hu.options );
		} );
			
		// listen for the parent
		window.addEventListener( 'message', function( event ) {
			var iframe = $( parent.document ).find( '#cn_iframe_id' ),
				form = $( parent.document ).find( '#cn-form-configure' );
			
			// console.log( iframe );
			
			// add spinner
			$( iframe ).closest( '.has-loader' ).addClass( 'cn-loading' ).append( '<span class="cn-spinner"></span>' );
			// lock options
			$( form ).addClass( 'cn-form-disabled' );
			
			// emit loader
			window.setTimeout( function() { 
				if ( typeof event.data == 'object' ) {
					var container = $( '#hu' ),
						option = event.data.call,
						customOptions = {},
						customTexts = {};

					switch ( option ) {
						case 'position':
							$( container ).removeClass( 'hu-position-bottom hu-position-top hu-position-left hu-position-right hu-position-center' );
							$( container ).addClass( 'hu-position-' + event.data.value );
							
							customOptions = { design: { position: event.data.value } }
							break;
						case 'purposes':
							// get last array item
							var last = event.data.value.slice( -1 )[0],
								message = cnFrontWelcome.cookieMessage[0];
							
							if ( typeof last !== 'undefined' ) {
								message = cnFrontWelcome.cookieMessage[parseInt( last )];
							}
							
							message += ' ' + cnFrontWelcome.preferencesMessage[0];
							
							customTexts = { bodyText: message }
							
							$( container ).find( '#hu-notice-message-container .hu-text-body' ).text( message );
							break;
						case 'laws':
							customOptions.config = {}
							
							if ( $.inArray( 'ccpa', event.data.value ) !== -1 ) {
								$( container ).find( '#hu-notice-dontsell-container' ).css( 'display', 'block' );
								
								$.extend( customOptions.config, { dontSellLink: true } );
							} else {
								$( container ).find( '#hu-notice-dontsell-container' ).css( 'display', 'none' );
								
								$.extend( customOptions.config, { dontSellLink: false } );
							}
							if ( $.inArray( 'gdpr', event.data.value ) !== -1 ) {
								$( container ).find( '#hu-notice-privacy-container' ).css( 'display', 'block' );
								
								$.extend( customOptions.config, { privacyPolicyLink: true } );
							} else {
								$( container ).find( '#hu-notice-privacy-container' ).css( 'display', 'none' );
								
								$.extend( customOptions.config, { privacyPolicyLink: false } );
							}
							
							// console.log( customOptions );
							break;
						case 'color_primary':
							var iframeContents = $( iframe ).contents()[0];
							iframeContents.documentElement.style.setProperty( '--hu-primaryColor', event.data.value );
							customOptions = { design: { primaryColor: event.data.value } }
							break;
							
						case 'color_background':
							var iframeContents = $( iframe ).contents()[0];
							iframeContents.documentElement.style.setProperty( '--hu-bannerColor', event.data.value );
							customOptions = { design: { bannerColor: event.data.value } }
							break;
							
						case 'color_border':
							var iframeContents = $( iframe ).contents()[0];
							iframeContents.documentElement.style.setProperty( '--hu-borderColor', event.data.value );
							customOptions = { design: { borderColor: event.data.value } }
							break;
							
						case 'color_text':
							var iframeContents = $( iframe ).contents()[0];
							iframeContents.documentElement.style.setProperty( '--hu-textColor', event.data.value );
							customOptions = { design: { textColor: event.data.value } }
							break;
							
						case 'color_heading':
							var iframeContents = $( iframe ).contents()[0];
							iframeContents.documentElement.style.setProperty( '--hu-headingColor', event.data.value );
							customOptions = { design: { headingColor: event.data.value } }
							break;
							
						case 'color_button_text':
							var iframeContents = $( iframe ).contents()[0];
							iframeContents.documentElement.style.setProperty( '--hu-btnTextColor', event.data.value );
							customOptions = { design: { btnTextColor: event.data.value } }
							break;
					}
					
					// set widget options
					hu.setOptions( customOptions );
					// set widget texts
					hu.setTexts( customTexts );
					
					// console.log( hu.options );
				}
				// remove spinner
				$( iframe ).closest( '.has-loader' ).find( '.cn-spinner' ).remove();
				$( iframe ).closest( '.has-loader' ).removeClass( 'cn-loading' );
				// unlock options
				$( form ).removeClass( 'cn-form-disabled' );
			}, 500	);
			
		}, false );

		// is it iframe?
		if ( document !== parent.document && typeof cnFrontWelcome !== 'undefined' && cnFrontWelcome.previewMode ) {
			// $( parent.document ).find( '#cn_test' ).val( $( document ).find( '.site-title' ).text() );
			var iframe = $( parent.document ).find( '#cn_iframe_id' );

			// inject links into initial document
			$( document.body ).find( 'a[href], area[href]' ).each( function() {
				cnAddPreviewModeToLink( this, iframe );
			} );

			// inject links into initial document
			$( document.body ).find( 'form' ).each( function() {
				cnAddPreviewModeToForm( this, iframe );
			} );

			// inject links for new elements added to the page
			if ( typeof MutationObserver !== 'undefined' ) {
				var observer = new MutationObserver( function( mutations ) {
					_.each( mutations, function( mutation ) {
						$( mutation.target ).find( 'a[href], area[href]' ).each( function() {
							cnAddPreviewModeToLink( this, iframe );
						} );

						$( mutation.target ).find( 'form' ).each( function() {
							cnAddPreviewModeToForm( this, iframe );
						} );
					} );
				} );

				observer.observe( document.documentElement, {
					childList: true,
					subtree: true
				} );
			} else {
				// If mutation observers aren't available, fallback to just-in-time injection.
				$( document.documentElement ).on( 'click focus mouseover', 'a[href], area[href]', function() {
					cnAddPreviewModeToLink( this, iframe );
				} );
			}

			// remove spinner
			$( iframe ).closest( '.has-loader' ).find( '.cn-spinner' ).remove();
			$( iframe ).closest( '.has-loader' ).removeClass( 'cn-loading' );
		}
	} );

	/**
	 * Inject preview mode parameter into specific links on the frontend.
	 */
	function cnAddPreviewModeToLink( element, iframe ) {
		var params, $element = $( element );

		// skip elements with no href attribute
		if ( ! element.hasAttribute( 'href' ) )
			return;

		// skip links in admin bar
		if ( $element.closest( '#wpadminbar' ).length )
			return;

		// ignore links with href="#", href="#id", or non-HTTP protocols (e.g. javascript: and mailto:)
		if ( '#' === $element.attr( 'href' ).substr( 0, 1 ) || ! /^https?:$/.test( element.protocol ) )
			return;

		// make sure links in preview use HTTPS if parent frame uses HTTPS.
		// if ( api.settings.channel && 'https' === api.preview.scheme.get() && 'http:' === element.protocol && -1 !== api.settings.url.allowedHosts.indexOf( element.host ) )
			// element.protocol = 'https:';

		// ignore links with special class
		if ( $element.hasClass( 'wp-playlist-caption' ) )
			return;

		// check special links
		if ( ! cnIsLinkPreviewable( element ) )
			return;

		$( element ).on( 'click', function() {
			$( iframe ).closest( '.has-loader' ).addClass( 'cn-loading' );
		} );

		// parse query string
		params = cnParseQueryString( element.search.substring( 1 ) );

		// set preview mode
		params.cn_preview_mode = 1;

		element.search = $.param( params );
	}

	/**
	 * Inject preview mode parameter into specific forms on the frontend.
	 */
	function cnAddPreviewModeToForm( element, iframe ) {
		var input = document.createElement( 'input' );

		input.setAttribute( 'type', 'hidden' );
		input.setAttribute( 'name', 'cn_preview_mode' );
		input.setAttribute( 'value', 1 );

		element.appendChild( input );
	}

	/**
	 * Parse query string.
	 */
	function cnParseQueryString( string ) {
		var params = {};

		_.each( string.split( '&' ), function( pair ) {
			var parts, key, value;

			parts = pair.split( '=', 2 );

			if ( ! parts[0] )
				return;

			key = decodeURIComponent( parts[0].replace( /\+/g, ' ' ) );
			key = key.replace( / /g, '_' );

			if ( _.isUndefined( parts[1] ) )
				value = null;
			else
				value = decodeURIComponent( parts[1].replace( /\+/g, ' ' ) );

			params[ key ] = value;
		} );

		return params;
	}

	/**
	 * Whether the supplied link is previewable.
	 */
	function cnIsLinkPreviewable( element ) {
		var matchesAllowedUrl, parsedAllowedUrl, elementHost;

		if ( 'javascript:' === element.protocol )
			return true;

		// only web URLs can be previewed
		if ( element.protocol !== 'https:' && element.protocol !== 'http:' )
			return false;

		elementHost = element.host.replace( /:(80|443)$/, '' );
		parsedAllowedUrl = document.createElement( 'a' );
		matchesAllowedUrl = ! _.isUndefined( _.find( cnFrontWelcome.allowedURLs, function( allowedUrl ) {
			parsedAllowedUrl.href = allowedUrl;

			return parsedAllowedUrl.protocol === element.protocol && parsedAllowedUrl.host.replace( /:(80|443)$/, '' ) === elementHost && 0 === element.pathname.indexOf( parsedAllowedUrl.pathname.replace( /\/$/, '' ) );
		} ) );

		if ( ! matchesAllowedUrl )
			return false;

		// skip wp login and signup pages
		if ( /\/wp-(login|signup)\.php$/.test( element.pathname ) )
			return false;

		// allow links to admin ajax as faux frontend URLs
		if ( /\/wp-admin\/admin-ajax\.php$/.test( element.pathname ) )
			return false;

		// disallow links to admin, includes, and content
		if ( /\/wp-(admin|includes|content)(\/|$)/.test( element.pathname ) )
			return false;

		return true;
	};

} )( jQuery );