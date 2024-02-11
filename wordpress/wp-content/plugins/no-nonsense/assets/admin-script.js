jQuery(function() {

	// Navigation
	jQuery('.r34nono-menu a').on('click', function() {
		jQuery(jQuery(this).attr('href')).attr('data-current', 'current').siblings().removeAttr('data-current');
		jQuery(this).attr('data-current', 'current').parent().siblings().find('a').removeAttr('data-current');
		if (jQuery(this).closest('.r34nono-menu').hasClass('r34nono-primary-menu')) {
			jQuery('.r34nono-secondary-menu').hide();
			jQuery('.r34nono-secondary-menu[data-section="' + jQuery(this).attr('href') + '"]').show().find('li:first-child > a').trigger('click');
		}
		if (history.pushState) {
			window.history.pushState({}, document.title, jQuery(this).attr('href'));
		}
		return false;
	});
	
	// Settings toggles
	jQuery('.r34nono-options-toggle').each(function() {
		var fn = jQuery(this).data('fn');
		if (jQuery('input[name="' + fn + '"][value="1"]').prop('checked')) {
			jQuery(this).show();
		}
		else {
			jQuery(this).hide();
		}
	});
	jQuery('form.r34nono-admin input[type=radio]').on('change', function() {
		var this_wrapper = jQuery(this).closest('tr');
		var this_options = this_wrapper.find('.r34nono-options-toggle');
		jQuery(this).parent('label').addClass('selected').siblings('label').removeClass('selected');
		jQuery('#export-settings-warning').show();
		jQuery('#export-settings-json').attr('disabled', true);
		if (this_options.length > 0) {
			if (jQuery(this).val() == 1) {
				this_options.slideDown();
			}
			else {
				this_options.slideUp();
			}
		}
	});
	
	// Submit activate
	jQuery('form.r34nono-admin input[type=radio], form.r34nono-admin input[type=checkbox]').on('change', function() {
		var frm = jQuery(this).closest('form.r34nono-admin');
		if (frm.find('input:checked').length > 0) {
			frm.find('input[type=submit]').removeClass('button-disabled');
		}
		else {
			frm.find('input[type=submit]').addClass('button-disabled');
		}
	});
	
	// Prevent inactive submission
	jQuery('form.r34nono-admin input[type=submit]').on('click', function() {
		if (jQuery(this).hasClass('button-disabled')) {
			return false;
		}
	});

});

document.addEventListener("DOMContentLoaded", function() {

	// Load page with correct tab selected
	if (jQuery('.r34nono').length > 0) {
		setTimeout(function() {
			var initial_tab = (location.hash && jQuery('.r34nono-menu a[href="' + location.hash + '"]').length > 0) ? location.hash : '#settings';
			jQuery('.r34nono-menu a[href="' + initial_tab + '"]').trigger('click');
			window.scrollTo(-100, -100);
		}, 1);
	}

});
