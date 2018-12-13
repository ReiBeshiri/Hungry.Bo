var total;
$(document).ready(function() {
	$( ".prod-qnt" ).bind("change-qnt", function() {
		var sum = 0;
	 	$("table>tbody>tr").each(function() {
			sum += Number($(this).find(".prod-qnt").val()) *
						Number($(this).find(".prod-price").html());
	 	});
	 	sum = sum.toFixed(2);
	 	total = "Total " + sum;	
		$("#totalcart").html(total);
	}).trigger("change-qnt");
	$( ".prod-qnt" ).change(function() {
		var sum = 0;
	 	$("table>tbody>tr").each(function() {
			sum += Number($(this).find(".prod-qnt").val()) *
						Number($(this).find(".prod-price").html());
	 	});
	 	sum = sum.toFixed(2);
	 	total = "Total " + sum;	
		$("#totalcart").html(total);
	}).trigger();
});