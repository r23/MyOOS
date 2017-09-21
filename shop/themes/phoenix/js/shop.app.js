
	window.width 	= jQuery(window).width();
	window.height 	= jQuery(window).height();

	/* Init */
	jQuery(window).ready(function () {


		// jQuery 3.x do no support size() - should be replaceced with .legth
		// We use this hack to make old plugins working
		jQuery.fn.extend({
		  size: function() {
		    return this.length;
		  }
		});

		// Init
		Init(false);


	});


/** Init
	Ajax Reinit:		Init(true);
 **************************************************************** **/
	function Init(is_ajax) {

		// First Load Only
		if(is_ajax != true) {
		
		//	_topNav();

		}


		/** Bootstrap Tooltip **/ 
		jQuery('[data-toggle="tooltip"]').tooltip({
			placement: $(this).data('placement'),
			html: true
		});

	}



