$(document).ready(function() {

	computeInitPrice();

	$(".prod-qnt").change(function() {
		computeDinamicallyPrice($(this));
	});

	$(window).bind("resize", function() {
			if ($(this).width() < 600) {
					$("tfoot.footer").empty();
					$("tfoot.footer").html('<tr><td colspan="4" class="text-center"><strong class="totalcart"></strong></td></tr> <tr><td colspan="4" class="text-center pay"><a href="#" class="btn btn-warning">Ordina e paga</a></td></tr>');
			} else {
				$("tfoot.footer").empty();
				$("tfoot.footer").html('<tr><td class="dummy-column"></td><td class="align-center"><strong class="totalcart"></strong></td><td class="text-center"><a href="#" class="btn btn-warning">Ordina e paga</a></td></tr>');
			}
			computeInitPrice();
	}).trigger('resize');

});

function computeDinamicallyPrice(ref) {
	var sum = 0;
	$(ref.parents("table").children("tbody").children("tr")).each(function() {
		sum += Number($(this).find(".prod-qnt").val()) *
					Number($(this).find(".prod-price").html());
	});
	sum = sum.toFixed(2);
	$(ref.parents("table").find("strong.totalcart")).html("Total: " + sum);
}

function computeInitPrice() {
	var tables = $("table");
	tables.each(function(){
		var sum = 0;
		$($(this).children("tbody").children("tr")).each(function() {
			sum += Number($(this).find(".prod-qnt").val()) *
						Number($(this).find(".prod-price").html());
		});
		sum = sum.toFixed(2);
		$($(this).find("strong.totalcart")).html("Total: " + sum);
	});
}
