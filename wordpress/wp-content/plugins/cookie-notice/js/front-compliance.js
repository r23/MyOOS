// cookieNotice
( function ( window, document, undefined ) {

	var cookieCompliance = new function () {

		// initialize
		this.init = function () {
			var _this = this;
			
			// on save data
			document.addEventListener( 'load-config.hu', function( event ) {
				
				// console.log( event );
				
				var config = event.detail;
				
				if ( config !== null ) {	
					// alpha JS request // no jQuery
					var request = new XMLHttpRequest();

					request.open( 'POST', cnComplianceArgs.ajaxUrl, true );
					request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
					request.onload = function () {
						if ( this.status >= 200 && this.status < 400 ) {
							// ff successful
						} else {
							// if fail
						}
					};
					request.onerror = function () {
						// connection error
					};
					request.send( 'action=cn_save_config&nonce=' + cnComplianceArgs.nonce + '&data=' + JSON.stringify( config ) );
				}

			} );
		};
	}

	// initialize plugin
	window.addEventListener( 'load', function () {
		cookieCompliance.init();
	}, false );

} )( window, document, undefined );