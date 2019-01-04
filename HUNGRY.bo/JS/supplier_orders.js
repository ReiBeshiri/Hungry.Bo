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


  $('#order-status-select').change(function() {
  	if($('#order-status-select').val() == 3){
  		$('#desc-other').removeAttr("hidden");
  	}else{
  		$('#desc-other').attr("hidden", "true");
  	}
  });

  $.post("../PHP/supplier_orders.php?request=orders", function(orders) {
    console.log(orders);

    var html_code = "";

    //Creazine della tabella.
    for(var i = 0; i < orders.length; i++) {
      html_code += '<tr><td headers="id" hidden>'+orders[i].ID+'</td><td headers="dest">'+orders[i].UsernameCliente+'</td><td headers="prod" class="order-state">'+orders[i].Stato+'</td><td headers="place">'+orders[i].LuogoConsegna+'</td><td headers="hour">'+(orders[i].Ora).slice(0,5)+'</td><td headers="notify"><a href="#" data-toggle="modal" data-target="#order-manage">Dettagli</a></td></tr>';
    }

    $("table>tbody").html(html_code);
  });

});
