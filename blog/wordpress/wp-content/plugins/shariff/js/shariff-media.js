// shariff media upload
jQuery(document).ready(function($){
	$("#shariff-upload-btn").click(function(e) {
		e.preventDefault();
		var image = wp.media({
			title: shariff_media.choose_image,
			multiple: false
		}).open()
		.on("select", function(e){
			// this will return the selected image from the media uploader, the result is an object
			var uploaded_image = image.state().get("selection").first();
			// output to the console uploaded_image
			console.log(uploaded_image);
			// convert uploaded_image to a JSON object to make accessing it easier
			var image_url = uploaded_image.toJSON().url;
			// assign the url value to the input field
			$("#shariff-image-url").val(image_url);
		});
	});
});
