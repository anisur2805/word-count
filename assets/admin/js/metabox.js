var frame;
(function ($) {
	// Set all variables to be used in scope
	var frame,
		gFrame,
		metaBox = $("#myImageMetaBox"), // Your meta box id here
		addImgLink = metaBox.find("#upload_image"),
		delImgLink = metaBox.find("#delete_custom_img"),
		imgContainer = metaBox.find("#image_container"),
		imgIdInput = metaBox.find("#obm_image_id"),
		imgURLInput = metaBox.find("#obm_image_url"),
		gMetaBox = $("#myGalleryMetaBox"), // Your meta box id here
		gAddImgLink = gMetaBox.find("#upload_images"),
		gDelImgLink = gMetaBox.find("#delete_custom_images"),
		gImgContainer = gMetaBox.find("#gallery_container"),
		gImgIdInput = gMetaBox.find("#obm_images_id"),
		gImgURLInput = gMetaBox.find("#obm_images_url");

	$(document).ready(function () {
		$("#omb_dp").datepicker();

		// for image upload
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

		frame.open();
		return false;
	});

	$(document).ready(function () {
		// for gallery image
		var images_url = $("#obm_images_url").val(); 
		images_url = images_url ? images_url.split(";") : [];
		for (var i in images_url) {
			let _image_url = images_url[i];
			// console.log("Splitted img ", _image_url);
			gImgContainer.html(`<img style="margin-right: 10px;" src='${_image_url}' />`);
		}

		// Gallery image
		gAddImgLink.on("click", function (event) {
			event.preventDefault();

			// If the media frame already exists, reopen it.
			if (gFrame) {
				gFrame.open();
				return;
			}

			// Create a new media frame
			gFrame = wp.media({
				title: "Select Gallery Images",
				button: {
					text: "Insert Image",
				},
				multiple: true, // Set to true to allow multiple files to be selected
			});

			// When an image is selected in the media frame...
			gFrame.on("select", function () {
				let images_ids = [];
				let images_urls = [];

				// Get media attachment details from the frame state
				let attachments = gFrame.state().get("selection").toJSON();

				// console.log("attachments", attachments);

				for (i in attachments) {
					var attachment = attachments[i];
					
					console.log("attachment", attachment);
					images_ids.push(attachment.id);
					images_urls.push(attachment.url);
					// Send the attachment URL to our custom image input field.
					gImgContainer.html(`<img  style="margin-right: 0px;" src='${attachment.sizes.full.url}' />`);

					// Hide the add image link
					gAddImgLink.addClass("hidden");

					// Unhide the remove image link
					gDelImgLink.removeClass("hidden");
				}
				// console.log("ids: ", images_ids, "urls: ", images_urls);

				gImgIdInput.val(images_ids.join(";"));
				gImgURLInput.val(images_urls.join(";"));
			});

			gFrame.open();
			return false;
		});
	});
})(jQuery);
