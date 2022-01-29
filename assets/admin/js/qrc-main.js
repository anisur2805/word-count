(function ($) {
	$(document).ready(function () {
		
		let current_val = $("#qrc_tgl").val(); 
		
		$(".toggle").minitoggle();
		$(".toggle").on("toggle", function (e) {
			console.log("hey")
			if (e.isActive) {
				$("#qrc_tgl").val(1);
				console.log("hello");
			} else {
				$("#qrc_tgl").val(0);
				console.log("hu");
				
			}
		});
	});
})(jQuery);
