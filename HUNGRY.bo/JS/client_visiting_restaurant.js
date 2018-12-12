$(document).ready(function() {
	$(window).bind("resize", function () {
      if ($(this).width() < 981) {
          $("#breakdiv").addClass("w-100");
      } else {
        $("#breakdiv").removeClass("w-100");
      }
  }).trigger('resize');
});