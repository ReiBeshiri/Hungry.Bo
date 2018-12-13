$(document).ready(function() {
	$(".prod-qnt").bind("change-qnt", function() {
		var sum = 0;
	 	$("table>tbody>tr").each(function() {
			sum += Number($(this).find(".prod-qnt").val()) *
						Number($(this).find(".prod-price").html());
	 	});
	 	sum = sum.toFixed(2);
		$("strong.totalcart").html("Total: " + sum);
	}).trigger("change-qnt");

	$(".prod-qnt").change(function() {
		var sum = 0;
	 	$("table>tbody>tr").each(function() {
			sum += Number($(this).find(".prod-qnt").val()) *
						Number($(this).find(".prod-price").html());
	 	});
	 	sum = sum.toFixed(2);
		$("strong.totalcart").html("Total: " + sum);
	}).trigger();

	$(window).bind("resize", function() {
			if ($(this).width() < 600) {
					$("tfoot.footer").empty();
					$("tfoot.footer").html('<tr><td colspan="3" class="align-center"><strong class="totalcart"></strong></td></tr> <tr><td colspan="3" class="text-right pay"><a href="#" class="btn btn-warning">Ordina e paga</a></td></tr>');
			} else {
				$("tfoot.footer").empty();
				$("tfoot.footer").html('<tr><td hidden></td><td class="align-center"><strong class="totalcart"></strong></td> <td class="text-right pay"><a href="#" class="btn btn-warning">Ordina e paga</a></td></tr>');
			}
	}).trigger('resize');

});
