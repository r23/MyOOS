jQuery(document).ready(function() {
	/**
	 * Upload.
	 */
	jQuery('#upload-image-button').click(function() {
		uploadfield = '#twoclick_buttons_postthumbnail';
		formfield = jQuery(uploadfield).attr('name');
		tbframe_interval = setInterval(function() {jQuery('#TB_iframeContent').contents().find('.savesend .button').val(twoclick_localizing_upload_js.use_this_image);}, 2000);
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');

		return false;
	});

	/**
	 * URL in das Eingebafeld einfügen.
	 */
	window.send_to_editor = function(html) {
		imgurl = jQuery('img', html).attr('src');
		jQuery(uploadfield).val(imgurl);
		tb_remove();
	}
});