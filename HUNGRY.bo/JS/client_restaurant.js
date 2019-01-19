$(document).ready(function () {

  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
        $("ul.btn-cart>li").empty();
        $("ul.btn-cart>li").html('<a href="./carrello.html"> <img src="../res/cart.png" width="60" height="60" alt=""/> </a>');
        $("tr>td>span.ingredients-in-table").attr("hidden", "true");
      } else {
        $("ul.btn-cart>li").empty();
        $("ul.btn-cart>li").html('<a href="./carrello.html"> <button id="cart" type="button" class="btn btn-outline-info">Carrello</button> </a>');
        $("tr>td>span.ingredients-in-table").removeAttr("hidden");
      }
  }).trigger('resize');

  var url_string = window.location.href;
  var url = new URL(url_string);
  var supplier = url.searchParams.get("supplier");

  console.log(supplier);
  var dataToSend = {
    username: supplier
  };

  $.post('../PHP/client_restaurant.php?request=informazioni-locale', dataToSend, function(data) {
    console.log(data);
    $("span#local-name").text(data[0].NomeLocale);
    if(data[0].OraApertura !== null && data[0].OraChiusura !== null) {
      $("span#local-time").text(data[0].OraApertura.slice(0,5) + "-" + data[0].OraChiusura.slice(0,5));
    }
    if(data[0].Icona !== null) {
      $("#container>div.dumb-container>#icon-container").html('<img class="img-fluid rounded-circle local-icon" src="../res/'+data[0].Icona+'" alt="local icon"/>');
    }
    if(data[0].Immagine !== null) {
      $("#container").css("background-image", "url('../res/"+data[0].Immagine+"')");
    }
  });

  //Creazione dinamica delle tabelle.
  $.post("../PHP/client_restaurant.php?request=lista-prodotti", dataToSend, function(products){
      $.getJSON("../PHP/dbRequestManager.php?request=tipologie-prodotti", function(type){
        for(var i = 0; i < type.length; i++) {
            var html_code = "";
            for(var j = 0; j < products.length; j++) {
              if(products[j].TipoProdotto === type[i].Nome) {
                  if(type[i].Nome == "Bibita") {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'</td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'€</td><td headers="select-'+type[i].Nome+'"><span class="table-select"><button type="button" class="btn btn-outline-info seleziona" data-toggle="modal" data-target="#order-popup">Seleziona</button></span></td></tr>';
                  } else {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'<br/><span class="ingredients-in-table">('+products[j].Ingredienti+')</span></td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'€</td><td headers="select-'+type[i].Nome+'"><span class="table-select"><button type="button" class="btn btn-outline-info seleziona" data-toggle="modal" data-target="#order-popup">Seleziona</button></span></td></tr>';
                  }
              }
            }
            $("table#"+type[i].Nome+">tbody").html(html_code);
        }
        window.parent.$(window.parent.document).trigger('resize');
      });
  });

  $("table").on("click", "button.seleziona", function() {
    var id_sel = $(this).parents("tr").children("td.id").text();
    var dataToSend = {
      id: id_sel
    };

    $("input#id").val(id_sel);

    $.post("../PHP/dbRequestManager.php?request=seleziona-prodotto", dataToSend, function(data){
      $("label#ingredients").append(data[0].Ingredienti);
    });
  });

  $("form#add-cart-form button").click(function() {
    var dataToSend = $("form#add-cart-form").serialize();

    $.post("../PHP/client_restaurant.php?request=aggiungi-al-carrello", dataToSend, function(data){
      if(data.status == "success") {
        location.reload();
      }
    });

  });

  //Set 5 s of timeout for check notifications
  setInterval(checkNotify, 5000);

  $("form#gestisci-notifiche").on('click', 'button.letta', function(){
    var span = $(this).parents("div.notifica").find("span.id-notifica");
    var id = span.text();
    var dataToSend = {
      id: id
    };
    console.log(id);
    $.post("../PHP/client_restaurant.php?request=rimuovi-notifica", dataToSend, function(data) {
      console.log(data);
      if(data.status == 'success') {
        span.parents("div.notifica").fadeOut("slow");
        updateNotifyNum();
      }
    });
  });
});

function checkNotify() {
  updateNotifyNum();
  $.getJSON("../PHP/client_restaurant.php?request=lista-notifiche", function(notify) {
    var html_code = "";
    for(var i = 0; i < notify.length; i++) {
      //Conteggio prodotti.
      var dataToSend = {
        id: notify[i].IDOrdine
      };
      $.ajax({
          url: "../PHP/client_restaurant.php?request=ordine-notifica",
          type: "POST",
          async: false,
          dataType: "json",
          data: dataToSend,
          success: function(order) {
            console.log(order[0].Stato == "Altro");
            if(order[0].Stato == "Altro") {
              html_code += '<div class="card-body notifica"><h6 class="card-title mittente">Notifica Ordine da: <strong>'+notify[i].Mittente+'</strong></h6><p class="card-text"><span class="id-notifica" hidden>'+notify[i].ID+'</span><ul><li><span class="luogo"><strong>Luogo Consegna: </strong></span>'+order[0].LuogoConsegna+'</li><li><span class="ora"><strong>Ora Consegna: </strong>'+order[0].Ora.slice(0,5)+'</span></li><li><span class="stato"><strong>Stato Ordine: </strong>'+order[0].Stato+'</span></li><li><span class="desc-ordine"><strong>Stato ordine cambiato: </strong>'+notify[i].Descrizione+'</span></li></p></ul><div class="text-right"><button class="btn btn-primary btn-sm letta" type="button">Segnala come letta</button></div></div>';
            } else {
              html_code += '<div class="card-body notifica"><h6 class="card-title mittente">Notifica Ordine da: <strong>'+notify[i].Mittente+'</strong></h6><p class="card-text"><span class="id-notifica" hidden>'+notify[i].ID+'</span><ul><li><span class="luogo"><strong>Luogo Consegna: </strong></span>'+order[0].LuogoConsegna+'</li><li><span class="ora"><strong>Ora Consegna: </strong>'+order[0].Ora.slice(0,5)+'</span></li><li><span class="stato"><strong>Stato Ordine: </strong>'+order[0].Stato+'</span></li></p></ul><div class="text-right"><button class="btn btn-primary btn-sm letta" type="button">Segnala come letta</button></div></div>';
            }
      }});
    }
    $("form#gestisci-notifiche").html(html_code);
  });
}

function updateNotifyNum() {
  $.getJSON("../PHP/client_restaurant.php?request=controllo-notifiche", function(data) {
    if(data.status == 'true') {
      //Inserire simbolo rosso di fianco a notifica. --> Da eliminare solo alla pressione.
      $("#numero-notifiche").html('<span class="badge badge-danger">'+data.count+'</span>');
    } else {
      $("#numero-notifiche").empty();
    }
  });
}
