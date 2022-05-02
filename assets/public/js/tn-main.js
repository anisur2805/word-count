(function ($) {
	$(document).ready(function () {
		if ($('.my-slider').length > 0) {
			var slider = tns({
				container: ".slider",
				// navContainer: "#customize-thumbnails",
				navAsThumbnails: true,
				autoplay: true,
				autoplayTimeout: 1000,
				autoplayButton: "#customize-toggle",
				swipeAngle: false,
				speed: 400,
			});
		}
	});
})(jQuery);
