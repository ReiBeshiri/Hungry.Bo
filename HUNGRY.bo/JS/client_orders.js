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
        console.log(arrayOrd);
        console.log(arrayDet);
        console.log(deliveryData);

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
            $("#tbody").append('<tr><td headers="id" hidden>'+ordersData[i]["ID"]+'</td><td headers="dest">'+ordersData[i]["UsernameCliente"]+'</td><td headers="prod" class="order-state">'+ordersData[i]["Stato"]+'</td><td headers="place">'+ordersData[i]["LuogoConsegna"]+'</td><td headers="hour">'+maxTime+'</td><td headers="details"><a href="#" data-toggle="modal" data-target="#order-details">Dettagli</a></td></tr>');
        }

    }
    });

});
