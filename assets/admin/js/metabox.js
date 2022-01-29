var frame;
(function ($) {
	// Set all variables to be used in scope
	var frame,
		metaBox = $("#myImageMetaBox"), // Your meta box id here
		addImgLink = metaBox.find("#upload_image"),
		delImgLink = metaBox.find("#delete_custom_img"),
		imgContainer = metaBox.find("#image_container"),
		imgIdInput = metaBox.find("#obm_image_id"),
		imgURLInput = metaBox.find("#obm_image_url");

	$(document).ready(function () {
		$("#omb_dp").datepicker();

		let image_url = $("#obm_image_url").val();

		if (image_url.length > 1) {
			imgContainer.html(`<img src='${image_url}' />`);
		}

		// upload image
		addImgLink.on("click", function (event) {
			event.preventDefault();

			// If the media frame already exists, reopen it.
			if (frame) {
				frame.open();
				return;
			}

			// Create a new media frame
			frame = wp.media({
				title: "Select Image",
				button: {
					text: "Insert Image",
				},
				multiple: false, // Set to true to allow multiple files to be selected
			});

			// When an image is selected in the media frame...
			frame.on("select", function () {
				// Get media attachment details from the frame state
				let attachment = frame.state().get("selection").first().toJSON();

				if (attachment) {
					// Send the attachment URL to our custom image input field.
					imgContainer.html(`<img src='${attachment.url}' />`);
					imgIdInput.val(attachment.id);
					imgURLInput.val(attachment.url);

					// Hide the add image link
					addImgLink.addClass("hidden");

					// Unhide the remove image link
					delImgLink.removeClass("hidden");
				}
			});

			frame.open();
			return false;
		});
	});
})(jQuery);
