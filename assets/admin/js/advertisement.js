(function ($) {
	"use strict";
	$(document).ready(function () {
            
            /* On every reload/ refresh re-fetch our media image 
            * this is only for our custom widget 
            * wordpress run a update on every window refresh/ reload 
            * here use the update event and call our method  
            */
		$(document).on("widget-updated", function (event, widget) {
			var widget_id = $(widget).attr("id");
			if (widget_id.indexOf("advertisement-widget") != -1) {
				prefetch();
			}
		});
            
            // first off the upload button event 
		$("body").off("click", "#ad_image_upload");
            
            // Upload button event binding with body based on event delegations  
		$("body").on("click", "#ad_image_upload", function () {
			var that = this,
				file_frame;
                  
                  // define some parameter once the popup opens 
			file_frame = wp.media.frames.file_frame = wp.media({
				// frame: "post",
				// frame: "select",
				// state: "insert",
				// multiple: false,

				className: "media-frame foundation-image-frame",
				frame: "select",
				multiple: true,
				title: "Select or Upload Media",
				library: {
					type: "image", // limits the frame to show only images
				},
				button: {
					text: "Select Image",
				},
			});
                  
                  // when select and insert image this will append in the img container
                  // and the img id will be stored as value of a hidden input field 
			file_frame.on("select", function () {
				var data = file_frame.state().get("selection");
				var jData = data.toJSON();

				// extract id from data list with the help of underscore
				var selection_ids = _.pluck(jData, "id");
				var container = $(that).siblings("#ad_image_preview");

				if (selection_ids.length > 0) {
					$(that).css("marginTop", "10px");
					$(that).val("change Image");
				}

				$(that).prev("input").val(selection_ids.join(","));
				$(that).prev("input").trigger("change"); // when change the image manually inform wordpress that we have a change 
                        
                        // first make empty the container 
				container.html("");
                        
                        // type check and insert img to the container otherwise log the error 
				data.map(function (attachment) {
					if (
						attachment.attributes.subtype == "png" ||
						attachment.attributes.subtype == "jpg" ||
						attachment.attributes.subtype == "jpeg"
					) {
						try {
							container.append(`<img class="test" src="${attachment.attributes.sizes.full.url}" />`);
						} catch (e) {
							console.log(e);
						}
					}
				});
			});
                  
                  // when we try to change img, 
                  // open the popup and our previous img will be shown as selected  
			file_frame.on("open", function () {
				var selection = file_frame.state().get("selection");
				var ats = $(that).prev("input").val().split(",");
                        
				for (var i = 0; i < ats.length; i++) {
					if (ats[i] > 0) {
						selection.add(wp.media.attachment(ats[i]));
					}
				}
			});
                  
                  // finally open the popup 
			file_frame.open();
                  
		});

		function prefetch() {
			$(".imgph").each(function () {
				var attrId = $(this).val();
				var container = $(this).prev();
				container.html("");
				
				if (attrId) {
					$(this).next().val("Change Image");
					var attachment = new wp.media.model.Attachment.get(attrId);
					attachment.fetch({
                                    success: function (attr) {
                                          
                                          container.append(
                                                `<img id="abc" src="${attr.attributes.sizes.full.url}" />`
                                                );
						},
					});
				}
			});
		}
		
		// check we are in customize mood
		if(wp.customize !== undefined) {
			$('.customize-control').on('expand', function () {
				var widget_id = $(this).attr('id');
				if( widget_id.indexOf('widget_advertisement')) {
					prefetch();
				}
				
			})
		}
		prefetch();
	});
})(jQuery);
