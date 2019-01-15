$(document).ready(function() {

	computeInitPrice();

	$("div#main-component").on("change", ".prod-qnt", function() {
		computeDinamicallyPrice($(this));
	});

	$(window).bind("resize", function() {
			if ($(this).width() < 600) {
					$("tfoot.footer").empty();
					$("tfoot.footer").html('<tr><td colspan="4" class="text-center"><strong class="totalcart"></strong></td></tr> <tr><td colspan="4" class="text-center pay"><a href="#" data-toggle="modal" data-target="#order-pay" class="btn btn-warning">Ordina e paga</a></td></tr>');
			} else {
				$("tfoot.footer").empty();
				$("tfoot.footer").html('<tr><td class="dummy-column"></td><td class="align-center"><strong class="totalcart"></strong></td><td class="text-center"><a href="#" data-toggle="modal" data-target="#order-pay" class="btn btn-warning">Ordina e paga</a></td></tr>');
			}

			if ($(this).width() < 768) {
				$("span.descrizione").attr("hidden", "true");
			} else {
				$("span.descrizione").removeAttr("hidden");
			}
			computeInitPrice();
	}).trigger('resize');

	$.post("../PHP/carrello.php?request=fornitori-in-carrello", function(suppliers) {
		var html_code = "";
		for(var i = 0; i < suppliers.length; i++) {
			html_code += '<div class="container"><div class="card text-center supplier-cart"><div class="card-body"><strong>'+suppliers[0].NomeLocale+'</strong><div class="text-center"><img class="d-inline-block img-fluid rounded-circle local-icon" src="../res/'+suppliers[0].Icona+'" alt="local icon"/></div><div class="col-12"><table class="table table-hover table-condensed"><thead><tr><th id="id'+suppliers[0].Username+'" hidden>ID</th><th id="product'+suppliers[0].Username+'">Prodotto</th><th id="price'+suppliers[0].Username+'">Prezzo</th><th id="qnt'+suppliers[0].Username+'">Quantità</th><th id="remove'+suppliers[0].Username+'" hidden>Remove</th></tr></thead><tbody>';

			var dataToSend = {
				usernameFornitore: suppliers[0].Username
			};

			$.ajax({
          url: "../PHP/carrello.php?request=prodotti-in-carrello",
          type: "POST",
          async: false,
          dataType: "json",
          data: dataToSend,
          success: function(products) {
						for(var i = 0; i < products.length; i++) {

							dataToSend = {
								id: products[i].IDProdotto
							};

							$.ajax({
									url: "../PHP/carrello.php?request=informazioni-prodotto",
									type: "POST",
									async: false,
									dataType: "json",
									data: dataToSend,
									success: function(info_product) {
										html_code += '<tr><td headers="id'+suppliers[0].Username+'" hidden>'+info_product[0].ID+'</td><td headers="product'+suppliers[0].Username+'"><div class="row"><span>'+info_product[0].Nome+'<br/><span class="descrizione">('+products[i].Descrizione+')</span></span></div></td><td class="prod-price" headers="price'+suppliers[0].Username+'">'+info_product[0].Prezzo+'</td><td headers="qnt'+suppliers[0].Username+'"><input type="number" class="form-control text-center prod-qnt" value="'+products[i].qnta+'" min="0" max="90" name="qnt"/></td><td headers="remove'+suppliers[0].Username+'"><input class="cancel" name="cancel" type="image" src="../res/croce.png" alt="immagine croce"/></td></tr>';
							}});

						}
      }});
			html_code += '</tbody><tfoot class="footer"></tfoot></table></div></div></div></div>';
		}

		$("div#main-component").html(html_code);
		window.parent.$(window.parent.document).trigger('resize');

	});

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
