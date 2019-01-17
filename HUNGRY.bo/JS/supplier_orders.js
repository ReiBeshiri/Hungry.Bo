$(document).ready(function () {

  $('#desc-other').attr("hidden", "true");

  $(window).bind("resize", function () {
      if ($(this).width() < 680) {
        $("td.order-state").attr("hidden", "true");
      	$("th#prod").attr("hidden", "true");
        $("td.luogo").attr("hidden", "true");
      	$("th#place").attr("hidden", "true");
      } else if ($(this).width() < 980) {
        $("td.order-state").attr("hidden", "true");
        $("th#prod").attr("hidden", "true");
        $("th#place").removeAttr("hidden");
        $("td.luogo").removeAttr("hidden");
      } else {
      	$("td.order-state").removeAttr("hidden");
		    $("th#prod").removeAttr("hidden");
        $("th#place").removeAttr("hidden");
        $("td.luogo").removeAttr("hidden");
      }
  }).trigger('resize');


  $('#order-status-select').change(function() {
  	if($('#order-status-select').val() == "Altro"){
  		$('#desc-other').removeAttr("hidden");
  	}else{
  		$('#desc-other').attr("hidden", "true");
  	}
  });

  $.post("../PHP/supplier_orders.php?request=orders", function(orders) {
    var html_code = "";

    //Creazine della tabella.
    for(var i = 0; i < orders.length; i++) {
      html_code += '<tr><td headers="id" class="id" hidden>'+orders[i].ID+'</td><td headers="dest" class="cliente">'+orders[i].UsernameCliente+'</td><td headers="prod" class="order-state">'+orders[i].Stato+'</td><td headers="place" class="luogo">'+orders[i].LuogoConsegna+'</td><td headers="hour" class="ora">'+(orders[i].Ora).slice(0,5)+'</td><td headers="notify"><a class="dettagli" href="#" data-toggle="modal" data-target="#order-manage">Dettagli</a></td></tr>';
    }

    $("table>tbody").html(html_code);
    window.parent.$(window.parent.document).trigger('resize');
  });

  $("tbody").on('click', 'a.dettagli', function(){
    var id = $(this).parents("tr").children("td.id").text();
    var luogo = $(this).parents("tr").children("td.luogo").text();
    var ora = $(this).parents("tr").children("td.ora").text();
    var cliente = $(this).parents("tr").children("td.cliente").text();
    var stato = $(this).parents("tr").children("td.order-state").text();

    var dataToSend = {
      id: id
    };

    $.post("../PHP/supplier_orders.php?request=details", dataToSend, function(pInOrder) {
      $("span#id-sel-order").text(id);
      $("span#cliente").text(cliente);
      $("span#luogo").text(luogo);
      $("span#ora").text(ora);
      $("select#order-status-select option[value="+stato+"]").attr('selected', 'selected');
      var html_code = "<ul>";
      for(var i = 0; i < pInOrder.length; i++) {
        var idProdotto = pInOrder[i].IDProdotto;

        var dataToSend = {
          id: idProdotto
        }
        $.ajax({
            url: "../PHP/supplier_orders.php?request=product-name",
            type: "POST",
            async: false,
            dataType: "json",
            data: dataToSend,
            success: function(name) {
              if(name.status == 'success') {
                html_code += '<li>'+name.nome+' <span class="qnta"> (x'+pInOrder[i].qnta+')</span><br/>('+pInOrder[i].Descrizione+')</li>';
              }
        }});
      }
      html_code += "</ul>";
      $("span#prodottiInOrdine").html(html_code);
    });
  });

  $("form#manage-order button").click(function() {
    var id = $("span#id-sel-order").text();
    var stato = $("#order-status-select").val();
    var descrizione = "";
    var destinatario = $("span#cliente").text();

    if(stato == "Altro") {
      descrizione += $("#textarea-desc").val();
    }

    var dataToSend = {
      id: id,
      stato: stato
    }

    $.post("../PHP/supplier_orders.php?request=update-status", dataToSend, function(dataRecv) {
      if(dataRecv.status == "success") {
        dataToSendNotify = {
          id: id,
          descrizione: descrizione,
          destinatario: destinatario
        }
        $.post("../PHP/supplier_orders.php?request=notify-client", dataToSendNotify, function(data) {
          if(data.status == 'success') {
            location.reload();
          }
        });
      }
    });
  });

});
