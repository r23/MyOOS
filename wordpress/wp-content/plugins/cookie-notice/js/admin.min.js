( function( $ ) {

    // ready event
	$( function() {
		// initialize color picker
		$( '.cn_color' ).wpColorPicker();
		
		$( '#cn_app_purge_cache a' ).on( 'click', function( e ) {
			e.preventDefault();
			
			var el = this;
			
			$( el ).parent().addClass( 'loading' ).append( '<span class="spinner is-active" style="float: none;"></span>' );
			
			$.ajax( {
				url: cnArgs.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'cn_purge_cache',
					nonce: cnArgs.nonce
				}
			} )
			.done ( function ( result ) {
				console.log( result );
			} )
			.always ( function ( result ) {
				$( el ).parent().find( '.spinner' ).remove();
			} );
		} );

		// refuse option
		$( '#cn_refuse_opt' ).on( 'change', function() {
			if ( $( this ).is( ':checked' ) )
				$( '#cn_refuse_opt_container' ).slideDown( 'fast' );
			else
				$( '#cn_refuse_opt_container' ).slideUp( 'fast' );
		} );
		
		// revoke option
		$( '#cn_revoke_cookies' ).on( 'change', function() {
			if ( $( this ).is( ':checked' ) )
				$( '#cn_revoke_opt_container' ).slideDown( 'fast' );
			else
				$( '#cn_revoke_opt_container' ).slideUp( 'fast' );
		} );

		// privacy policy option
		$( '#cn_see_more' ).on( 'change', function() {
			if ( $( this ).is( ':checked' ) )
				$( '#cn_see_more_opt' ).slideDown( 'fast' );
			else
				$( '#cn_see_more_opt' ).slideUp( 'fast' );
		} );

		// on scroll option
		$( '#cn_on_scroll' ).on( 'change', function() {
			if ( $( this ).is( ':checked' ) )
				$( '#cn_on_scroll_offset' ).slideDown( 'fast' );
			else
				$( '#cn_on_scroll_offset' ).slideUp( 'fast' );
		} );

		// privacy policy link
		$( '#cn_see_more_link-custom, #cn_see_more_link-page' ).on( 'change', function() {
			if ( $( '#cn_see_more_link-custom:checked' ).val() === 'custom' ) {
				$( '#cn_see_more_opt_page' ).slideUp( 'fast', function() {
					$( '#cn_see_more_opt_link' ).slideDown( 'fast' );
				} );
			} else if ( $( '#cn_see_more_link-page:checked' ).val() === 'page' ) {
				$( '#cn_see_more_opt_link' ).slideUp( 'fast', function() {
					$( '#cn_see_more_opt_page' ).slideDown( 'fast' );
				} );
			}
		} );
		
		$( '#cn_refuse_code_fields' ).find( 'a' ).on( 'click', function( e ) {
			e.preventDefault();

			$( '#cn_refuse_code_fields' ).find( 'a' ).removeClass( 'nav-tab-active' );
			$( '.refuse-code-tab' ).removeClass( 'active' );

			var id = $( this ).attr( 'id' ).replace( '-tab', '' );

			$( '#' + id ).addClass( 'active' );
			$( this ).addClass( 'nav-tab-active' );
		} );
    } );

	$( document ).on( 'click', 'input#reset_cookie_notice_options', function() {
		return confirm( cnArgs.resetToDefaults );
	} );

} )( jQuery );