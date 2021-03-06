var email;
$(document).ready(function(){
  var icona;
  var immagine;

  $("div.alert").hide();

  $.getJSON("../PHP/dbRequestManager.php?request=tipologie-prodotti", function(data){
    var html_code = "";
    for(var i = 0; i < data.length; i++){
        html_code += "<option value='"+data[i]["Nome"]+"'>"+data[i]["Nome"]+"</option>";
    }
    $("#inlineFormCustomSelect").html(html_code);
    $("#plate-type").html(html_code);
  });

  $("#custom-icon").change(function(){
    icona = ($("#custom-icon").val()).split("\\");
    icona = icona[icona.length-1];
    $("#icon-label").text("Icona: "+$("#custom-icon").val());
  });

  $("#custom-img").change(function(){
    immagine = ($("#custom-img").val()).split("\\");
    immagine = immagine[immagine.length-1];
    $("#image-label").text("Immagine: "+$("#custom-img").val());
  });

  $(window).bind("resize", function () {
      if ($(this).width() < 980) {
        $("tr>td>span.ingredients-in-table").attr("hidden", "true");
      } else {
        $("tr>td>span.ingredients-in-table").removeAttr("hidden");
      }
  }).trigger('resize');

  $.getJSON("../PHP/supplier_home.php?request=informazioni-locale", function(data){
    $("span#local-name").text(data[0].NomeLocale);
    $("span#local-address").text(data[0].Indirizzo);
    if(data[0].Email !== null && data[0].Email.length > 0) {
      $("span#local-email").text(data[0].Email);
      email = data[0].Email;
    }
    if(data[0].OraApertura != null && data[0].OraChiusura != null) {
      $("span#local-time").text(data[0].OraApertura.slice(0,5) + "-" + data[0].OraChiusura.slice(0,5));
    }
    if(data[0].Icona != null) {
    $("#container>div.dumb-container>#icon-container").html('<img class="img-fluid rounded-circle local-icon" src="../res/'+data[0].Icona+'" alt="local icon"/>');
    }

    if(data[0].Immagine != null) {
      $("#container").css("background-image", "url('../res/"+data[0].Immagine+"')");
    }
  });

  $("#btn-nav-gestisci-locale").click(function() {
    $("input#mod-email").val(email);
  });

  $("#gestisci-locale-submit").click(function(){
    event.preventDefault();

    var email = $("input#mod-email").val();

    if(email.length == 0 || (email.length > 0 && validateEmail(email))) {
      var dataToSend = {
        icona: icona,
        immagine: immagine,
        email: email,
      };

      console.log(dataToSend);
      $.post("../PHP/supplier_home.php?request=gestisci-locale", dataToSend, function(data) {
        console.log(data.status);
        location.reload();
      });
    } else {
      var error = "Errore nella mail.";
      $("div.alert").html(error);
      $("div.alert").show();
    }
  });

  $("form#modifica-orario button").click(function(){
    var open = $("#open");
    var close = $("#close");
    var dataToSend = $("form#modifica-orario").serialize();
    $.post("../PHP/supplier_home.php?request=modifica-orari", dataToSend, function(data){
      console.log(data);
      location.reload();
    });
  });

  $("form#inserisci-prodotto button").click(function(){
    var dataToSend = $("form#inserisci-prodotto").serialize();
    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=aggiungi-prodotto", dataToSend, function(data){
      console.log(data);
      location.reload();
    });
  });

  //Creazione dinamica delle tabelle.
  $.post("../PHP/supplier_home.php?request=lista-prodotti", function(products){
      $.getJSON("../PHP/dbRequestManager.php?request=tipologie-prodotti", function(type){
        for(var i = 0; i < type.length; i++) {
            var html_code = "";
            for(var j = 0; j < products.length; j++) {
              if(products[j].TipoProdotto === type[i].Nome) {
                  if(type[i].Nome == "Bibita" || products[j].Ingredienti.length === 0) {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'</td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'???</td><td headers="modify-'+type[i].Nome+'"><span class="table-modify"><button type="button" class="btn btn-outline-info modifica" data-toggle="modal" data-target="#modify-popup">Modifica</button></span></td></tr>';
                  } else {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'<br/><span class="ingredients-in-table">('+products[j].Ingredienti+')</span></td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'???</td><td headers="modify-'+type[i].Nome+'"><span class="table-modify"><button type="button" class="btn btn-outline-info modifica" data-toggle="modal" data-target="#modify-popup">Modifica</button></span></td></tr>';
                  }
              }
            }
            $("table#"+type[i].Nome+">tbody").html(html_code);
        }
        window.parent.$(window.parent.document).trigger('resize');
      });
  });

  //Pressione bottone modifica
  //N.B: In questo modo attacco l'evento al tbody gi?? presente e cos?? verr?? visualizzato e ancorato anche ad
  //elementi creati dinamicamente.
  $("tbody").on('click', 'button.modifica', function(){
    var id_sel = $(this).parents("tr").children("td.id").text();
    var dataToSend = {
      id: id_sel
    };
    $.post("../PHP/dbRequestManager.php?request=seleziona-prodotto", dataToSend, function(data){
      $("input#id").val(data[0].ID);
      $("input#enter-name-prod").val(data[0].Nome);
      $("textarea#insert-ingredients-prod").val(data[0].Ingredienti);
      $("input#insert-price-prod").val(data[0].Prezzo);
      $("input#insert-preapare-time-prod").val(data[0].TempoPreparazione);
      $("select#plate-type option[value="+data[0].TipoProdotto+"]").attr('selected', 'selected');
    });
  });

  $("form#modifica-prodotto button#submit").click(function(){
    var dataToSend = $("form#modifica-prodotto").serialize();
    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=modifica-prodotto", dataToSend, function(data){
      location.reload();
    });
  });

  $("form#modifica-prodotto button#remove").click(function(){
    var id_sel = $("input#id").val();
    var dataToSend = {
      id: id_sel
    };
    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=rimuovi-prodotto", dataToSend, function(data){
      if(data.status == "success") {
        location.reload();
      }
    });
  });

  //Set 1s of timeout for check notifications
  setInterval(checkNotify, 1000);

  $("form#gestisci-notifiche").on('click', 'button.letta', function(){
    var span = $(this).parents("div.notifica").find("span.id-notifica");
    var id = span.text();
    var dataToSend = {
      id: id
    };
    console.log(id);
    $.post("../PHP/supplier_home.php?request=rimuovi-notifica", dataToSend, function(data) {
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
  $.getJSON("../PHP/supplier_home.php?request=lista-notifiche", function(notify) {
    var html_code = "";
    for(var i = 0; i < notify.length; i++) {
      if(notify[i].Mittente.toUpperCase() == "ADMIN" && notify[i].IDOrdine == null) {
          html_code+='<div class="card mb-2"><div class="card-body notifica"><h6 style="color:red;" class="card-title mittente">Hai una notifica da <strong>'+notify[i].Mittente.toUpperCase()+'</strong></h6><p class="card-text"><span class="id-notifica" hidden>'+notify[i].ID+'</span><ul><li><span class="desc"><strong>Descrizione: </strong>'+notify[i].Descrizione+'</span></li></p></ul><div class="text-right"><button class="btn btn-primary btn-sm letta" type="button">Segnala come letta</button></div></div></div>';
      } else {
        //Conteggio prodotti.
        var dataToSend = {
          id: notify[i].IDOrdine
        };

        $.ajax({
          url: "../PHP/supplier_home.php?request=conta-prodotti",
          type: "POST",
          async: false,
          dataType: "json",
          data: dataToSend,
          success: function(nProd) {

            if(nProd.status !== 'false') {
            $.ajax({
                url: "../PHP/supplier_home.php?request=ordine-notifica",
                type: "POST",
                async: false,
                dataType: "json",
                data: dataToSend,
                success: function(order) {
                  html_code+='<div class="cardmb-2"><div class="card-body notifica"><h6 class="card-title mittente">Hai un nuovo ordine da <strong>'+notify[i].Mittente+'</strong></h6><p class="card-text"><span class="id-notifica" hidden>'+notify[i].ID+'</span><ul><li><span class="prodotti"><strong>Numero Prodotti: </strong>'+nProd.count+'</span></li><li><span class="luogo"><strong>Luogo Consegna: </strong></span>'+order[0].LuogoConsegna+'</li><li><span class="ora"><strong>Ora Consegna: </strong>'+order[0].Ora.slice(0,5)+'</span></li></p></ul><div class="text-right"><button class="btn btn-primary btn-sm letta" type="button">Segnala come letta</button></div></div></div>';
            }});
          }
        }});
      }
    }
    $("form#gestisci-notifiche").html(html_code);
  });
}

function updateNotifyNum() {
  $.getJSON("../PHP/supplier_home.php?request=controllo-notifiche", function(data) {
    if(data.status == 'true') {
      //Inserire simbolo rosso di fianco a notifica. --> Da eliminare solo alla pressione.
      $("#numero-notifiche").html('<span class="badge badge-danger">'+data.count+'</span>');
      if($(window).width() <= 981) {
        $("span.badge-notify").text(data.count);
      } else {
        $("span.badge-notify").empty();
      }
    } else {
      $("#numero-notifiche").empty();
      $("span.badge-notify").empty();
    }
  });
}

function validateEmail(email) {
  var regex = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
  return regex.test(email);
}
