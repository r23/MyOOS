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

		
		//nivo slider
		$('.nivo-slider').nivoSlider({
			effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
			slices: 15, // For slice animations
			boxCols: 8, // For box animations
			boxRows: 4, // For box animations
			animSpeed: 500, // Slide transition speed
			pauseTime: 5000, // How long each slide will show
			startSlide: 0, // Set starting Slide (0 index)
			directionNav: true, // Next & Prev navigation
			controlNav: false, // 1,2,3... navigation
			controlNavThumbs: false, // Use thumbnails for Control Nav
			pauseOnHover: true, // Stop animation while hovering
			manualAdvance: false, // Force manual transitions
			prevText: '', // Prev directionNav text
			nextText: '', // Next directionNav text
			randomStart: false, // Start on a random slide
			beforeChange: function(){}, // Triggers before a slide transition
			afterChange: function(){}, // Triggers after a slide transition
			slideshowEnd: function(){}, // Triggers after all slides have been shown
			lastSlide: function(){}, // Triggers when last slide is shown
			afterLoad: function(){} // Triggers when slider has loaded
		});

});