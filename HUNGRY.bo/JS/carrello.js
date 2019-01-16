$(document).ready(function() {

	computeInitPrice();

	$.post("../PHP/carrello.php?request=luoghi-consegna", function(data) {
		var html_code = "";
		for(var i = 0; i < data.length; i++) {
			html_code +='<option value="'+data[i].Nome+'">'+data[i].Nome+'</option>';
		}
		$("select#delivery-place").html(html_code);
	});

	$("div#main-component").on("change", ".prod-qnt", function() {
		computeDinamicallyPrice($(this));
		var id = $(this).parents("tr").find("td.id").text();

		var dataToSend = {
			id:id,
			qnta: $(this).val()
		};

		$.post("../PHP/carrello.php?request=update-qnta", dataToSend, function(data) {
			console.log(data);
		});
	});

	$("div#main-component").on("click", ".cancel", function() {
		alert("Prodotto eliminato dal carrello.");
		var id = $(this).parents("tr").find("td.id").text();
		var dataToSend = {
			id: id
		};

		$.post("../PHP/carrello.php?request=rimuovi-prodotto", dataToSend, function(data) {
			console.log(data);
			if(data.status == "success") {
				location.reload();
			}
		});
	});

	$(window).bind("resize", function() {
			if ($(this).width() < 600) {
					$("tfoot.footer").empty();
					$("tfoot.footer").html('<tr><td colspan="4" class="text-center"><strong class="totalcart"></strong></td></tr> <tr><td colspan="4" class="text-center pay"><a href="#" data-toggle="modal" data-target="#order-pay" class="btn btn-warning pay">Ordina e paga</a></td></tr>');
			} else {
				$("tfoot.footer").empty();
				$("tfoot.footer").html('<tr><td class="dummy-column"></td><td class="align-center"><strong class="totalcart"></strong></td><td class="text-center"><a href="#" data-toggle="modal" data-target="#order-pay" class="btn btn-warning pay">Ordina e paga</a></td></tr>');
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
		console.log(suppliers);
		for(var i = 0; i < suppliers.length; i++) {
			html_code += '<div class="container"><div class="card text-center supplier-cart"><div class="card-body"><span id="supplier-username" hidden>'+suppliers[i].Username+'</span><strong>'+suppliers[i].NomeLocale+'</strong><div class="text-center"><img class="d-inline-block img-fluid rounded-circle local-icon" src="../res/'+suppliers[i].Icona+'" alt="local icon"/></div><div class="col-12"><table class="table table-hover table-condensed"><thead><tr><th id="id'+suppliers[i].Username+'" hidden>ID</th><th id="product'+suppliers[i].Username+'">Prodotto</th><th id="price'+suppliers[i].Username+'">Prezzo</th><th id="qnt'+suppliers[i].Username+'">Quantità</th><th id="remove'+suppliers[i].Username+'" hidden>Remove</th></tr></thead><tbody>';
			var dataToSend = {
				usernameFornitore: suppliers[i].Username
			};

			$.ajax({
          url: "../PHP/carrello.php?request=prodotti-in-carrello",
          type: "POST",
          async: false,
          dataType: "json",
          data: dataToSend,
          success: function(products) {
						console.log(products);
						for(var j = 0; j < products.length; j++) {

							dataToSend = {
								id: products[j].IDProdotto
							};

							$.ajax({
									url: "../PHP/carrello.php?request=informazioni-prodotto",
									type: "POST",
									async: false,
									dataType: "json",
									data: dataToSend,
									success: function(info_product) {
										console.log(info_product);
										html_code += '<tr><td class="id" headers="id'+suppliers[i].Username+'" hidden>'+products[j].ID+'</td><td headers="product'+suppliers[i].Username+'"><div class="row"><span>'+info_product[0].Nome+'<br/><span class="descrizione">('+products[j].Descrizione+')</span></span></div></td><td class="prod-price" headers="price'+suppliers[i].Username+'">'+info_product[0].Prezzo+'</td><td headers="qnt'+suppliers[i].Username+'"><input type="number" class="form-control text-center prod-qnt" value="'+products[j].qnta+'" min="0" max="90" name="qnt"/></td><td headers="remove'+suppliers[i].Username+'"><input class="cancel" name="cancel" type="image" src="../res/croce.png" alt="immagine croce"/></td></tr>';
							}});

						}
      }});
			html_code += '</tbody><tfoot class="footer"></tfoot></table></div></div></div></div>';
		}

		$("div#main-component").html(html_code);
		window.parent.$(window.parent.document).trigger('resize');

	});

	$("div#main-component").on("click", ".pay", function() {
		var username = $(this).parents("div.card-body").find("span#supplier-username").text();
		$("input#username").val(username);

		var ids = [];
		var username = $(this).parents("div.card-body").find("td.id").each(function(id) {
			ids.push($(this).text());
		});

		$("form#order-pay button").click(function() {
			var dataToSend = {
				ids: ids,
				username: $("input#username").val(),
				cvv: $("input#cvv").val(),
				num: $("input#card-num").val(),
				hour: $("input#delivery-hour").val(),
				place: $("select#delivery-place option:selected").text()
			};

			console.log(dataToSend);

			$.post("../PHP/carrello.php?request=ordine-effettuato", dataToSend, function(data) {
				if(data.status == "success") {
					location.reload();
				}
			});
		});
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
