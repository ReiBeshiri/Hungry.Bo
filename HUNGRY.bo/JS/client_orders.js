$(document).ready(function(){
    $(window).bind("resize", function () {
        if ($(this).width() < 981) {
            $("td.order-state").attr("hidden", "true");
            $("th.order-prod").attr("hidden", "true");
        } else {
            $("td.order-state").removeAttr("hidden");
            $("th.order-prod").removeAttr("hidden");
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
        var maxTime = 0;
        //console.log(ordersData);
        //console.log(productsInOrdersData);
        //console.log(productsData);

        //filter
        for (var i = 0; i < ordersData.length; i++) {
          arrayOrd.push(ordersData[i]);
          for (var j = 0; j < productsInOrdersData.length; j++) {
            if(ordersData[i]["ID"] === productsInOrdersData[j]["IDOrdine"]){
              arrayOrd.push(productsInOrdersData[j]);
            }
            for (var k = 0; k < productsData.length; k++) {
              if(productsInOrdersData[j]["IDProdotto"] === productsData[k]["ID"]){
                arrayDet.push(productsData[k]);
              }
            }
          }
        }
        ///console.log(arrayOrd);
        //console.log(arrayDet);
        //console.log(deliveryData);

        for(var i = 0; i < ordersData.length; i++){
            //ora Consegna
            for (var j = 0; j < productsData.length; j++) {
              if(ordersData[i]["UsernameFornitore"] === productsData[j]["UsernameFornitore"]){
                if(parseInt(productsData[j]["TempoPreparazione"]) > parseInt(maxTime)){
                  maxTime = productsData[j]["TempoPreparazione"];
                }
              }
              //sommo maxTime a TempoArrivoCampus
              for(var k = 0; k < deliveryData.length; k++){
                if(ordersData[i]["UsernameFornitore"] === deliveryData[k]["Username"]){
                  maxTime = parseInt(maxTime) + parseInt(deliveryData[k]["TempoArrivoCampus"]);
                }
              }
          }
            $("#tbody").append('<tr><td headers="id" hidden class="hiddentd">'+ordersData[i]["ID"]+'</td><td headers="dest">'+ordersData[i]["UsernameCliente"]+'</td><td headers="prod" class="order-state">'+ordersData[i]["Stato"]+'</td><td headers="place">'+ordersData[i]["LuogoConsegna"]+'</td><td headers="hour">'+maxTime+'</td><td headers="details"><a href="#" data-toggle="modal" data-target="#order-details" class="details">Dettagli</a></td></tr>');
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
          var str1 = '<div class="form-group"><p>Mittente: <span>'+output[0]+'</span></p></div><div class="form-group">Ordine:<ul>';

          var str3 = '</ul></div><div class="form-group"><p>Stato: <span>'+spedizione+'</span></p></div><div class="form-group"><p>Luogo: <span>'+luogoConsegna+'</span></p></div><div class="form-group"><p>Ora: <span>'+oraConsegna+'</span></p></div>';

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
