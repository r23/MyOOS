function checkAll(pForm)
{
	for (i = 0, n = pForm.elements.length; i < n; i++)
	{
		var objName = pForm.elements[i].name;
		var objType = pForm.elements[i].type;
		if (objType == 'checkbox' && objName != 'checkme')
		{
			box = eval(pForm.elements[i]);
			box.checked = !box.checked;
		}
	}
}


/*global jQuery:false */
jQuery(document).ready(function($) {
"use strict";

				
		// tooltip
		$('.social-network li a, .options_box .color a').tooltip();

		
		//scroll to top
		$(window).scroll(function(){
			if ($(this).scrollTop() > 100) {
				$('.scrollup').fadeIn();
				} else {
				$('.scrollup').fadeOut();
			}
		});
		$('.scrollup').click(function(){
			$("html, body").animate({ 
				scrollTop: 0 
			}, 800);
			return false;
		});

});