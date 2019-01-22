$(document).ready(function(){
    $(window).bind("resize", function () {
        if ($(this).width() < 981) {
            $("td.order-state").attr("hidden", "true");
            $("th.order-prod").attr("hidden", "true");
        } else {
            $("td.order-state").removeAttr("hidden");
            $("th.order-prod").removeAttr("hidden");
        }
        if ($(this).width() <= 320) {
          $("th#place").attr("hidden", "true");
          $("td.placehide").attr("hidden", "true");
        } else {
          $("th#place").removeAttr("hidden");
          $("td.placehide").removeAttr("hidden");
        }
    }).trigger('resize');


    $.getJSON("../PHP/client_orders.php?request=orders", function(data) {

      if(data != null) {

        var output = data;
        var ordersData = output[0];
        var productsInOrdersData = output[1];
        var productsData = output[2];
        var deliveryData = output[3];
        var arrayOrd = [];
        var arrayDet = [];
        console.log(ordersData);
        console.log(productsInOrdersData);
        console.log(productsData);
        //console.log(ordersData[0]["ID"]);
        for (var i = 0; i < ordersData.length; i++) {
          $("#tbody").append('<tr><td headers="id" hidden class="hiddentd">'+ordersData[i]["ID"]+'</td><td headers="mitt">'+ordersData[i]["UsernameFornitore"]+'</td><td headers="prod" class="order-state">'+ordersData[i]["Stato"]+'</td><td headers="place" class="placehide">'+ordersData[i]["LuogoConsegna"]+'</td><td headers="hour">'+ordersData[i]["Ora"].slice(0,5)+'</td><td headers="details"><a href="#" data-toggle="modal" data-target="#order-details" class="details">Dettagli</a></td></tr>');
          window.parent.$(window.parent.document).trigger('resize');
        }
    } else {
      console.log("An error in the Server as occurred");
    }
    });

    /*<div class="form-group">
      <p>Mittente: <span>Chianì</span></p>
    </div>
    <div class="form-group">
      Ordine:
      <ul>
        <li>margherita <span>(Modifiche)</span></li>
        <li>diavola <span>(Modifiche)</span></li>
        <li>5 stagioni <span>(Modifiche)</span></li>
      </ul>
    </div>
    <div class="form-group">
      <p>Stato: <span>Non ancora spedito</span></p>
    </div>
    <div class="form-group">
      <p>Luogo: <span>aula 2.2</span></p>
    </div>
    <div class="form-group">
      <p>Ora: <span>12:30</span></p>
    </div>*/
    $("#tbody").on('click', 'a.details', function(){  //attacco dinamicamente a tbody l'onclick agli ancor class details

      idOrder = $(this).parents("tr").children("td.hiddentd").text();
      spedizione = $(this).parents("tr").children("td.hiddentd").next().next().text();
      luogoConsegna = $(this).parents("tr").children("td.hiddentd").next().next().next().text();
      oraConsegna = $(this).parents("tr").children("td.hiddentd").next().next().next().next().text();

      //prendo l'id dell'oridine
      var dataToSend = {
        id: idOrder
      };

      $.post("../PHP/client_orders.php?request=details", dataToSend, function(data) {

        if(data != null) {

          var output = data;
          var str2 = '';
          var str1 = '<div class="form-group"><p><strong>Mittente: </strong><span>'+output[0]+'</span></p></div><div class="form-group"><strong>Ordine:</strong><ul>';

          var str3 = '</ul></div><div class="form-group"><p><strong>Stato: </strong><span>'+spedizione+'</span></p></div><div class="form-group"><p><strong>Luogo: </strong><span>'+luogoConsegna+'</span></p></div><div class="form-group"><p><strong>Ora: </strong><span>'+oraConsegna.slice(0,5)+'</span></p></div>';

          for (var i = 1; i < output.length; i++) {
            if(output[i]["Descrizione"] === null){
              output[i]["Descrizione"] = "";
            }
            str2 += '<li>'+output[i][0] +'<span>'+'  '+output[i]["Descrizione"]+'</span></li>';
          }

          var str = str1+str2+str3;

          $("div.modal-body").empty();
          $("div.modal-body").append(str);

        } else {

          console.log("An error in the Server as occurred");

        }

      });
    });

});
//new Date().toLocaleTimeString('en-US', { hour12: false});
