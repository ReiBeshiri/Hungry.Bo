$(document).ready(function() {
	$( ".prod-qnt" ).change(function() {
		var sum = 0;
	 	$("table>tbody>tr").each(function() {
			sum += Number($(this).find(".prod-qnt").val()) *
						Number($(this).find(".prod-price").html());
	 	});
		console.log(sum);
	});

});
