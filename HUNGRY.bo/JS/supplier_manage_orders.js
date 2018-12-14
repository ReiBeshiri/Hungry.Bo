$(document).ready(function () {

  $('#desc-other').attr("hidden", "true");

  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
          $("td.order-state").attr("hidden", "true");
      	  $("th.order-prod").attr("hidden", "true");
      } else {
      	  $("td.order-state").removeAttr("hidden");
		  $("th.order-prod").removeAttr("hidden");
      }
  }).trigger('resize');


  $('#inlineFormCustomSelect').change(function() {
  	if($('#inlineFormCustomSelect').val() == 3){
  		$('#desc-other').removeAttr("hidden");
  	}else{
  		$('#desc-other').attr("hidden", "true");
  	}
  });

});