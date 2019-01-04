$(document).ready(function(){
  var icona;
  var immagine;

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
    $("span#local-time").text(data[0].OraApertura.slice(0,5) + "-" + data[0].OraApertura.slice(0,5));
    $("#container>div.dumb-container>#icon-container").html('<img class="img-fluid rounded-circle local-icon" src="../res/'+data[0].Icona+'" alt="local icon"/>');
    $("#container").css("background-image", "url('../res/"+data[0].Immagine+"')");
  });

  $("#gestisci-locale-submit").click(function(){
    event.preventDefault();

    var dataToSend = {
      icona: icona,
      immagine: immagine
    };

    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=aggiungi-immagini", dataToSend, function(data) {
      console.log(data.status);
      location.reload();
    });
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
                  if(type[i].Nome == "Bibita") {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'</td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'€</td><td headers="modify-'+type[i].Nome+'"><span class="table-modify"><button type="button" class="btn btn-outline-info modifica" data-toggle="modal" data-target="#modify-popup">Modifica</button></span></td></tr>';
                  } else {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'<br/><span class="ingredients-in-table">('+products[j].Ingredienti+')</span></td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'€</td><td headers="modify-'+type[i].Nome+'"><span class="table-modify"><button type="button" class="btn btn-outline-info modifica" data-toggle="modal" data-target="#modify-popup">Modifica</button></span></td></tr>';
                  }
              }
            }
            $("table#"+type[i].Nome+">tbody").html(html_code);
        }
        window.parent.$(window.parent.document).trigger('complete');
      });
  });

  //Pressione bottone modifica
  //N.B: In questo modo attacco l'evento al tbody già presente e così verrà visualizzato e ancorato anche ad
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
      location.reload();
    });
  });

  //Set 5 s of timeout for check notifications
  setInterval(checkNotify, 5000);

  $("form#gestisci-notifiche").on('click', 'button.letta', function(){
    var span = $(this).parents("form#gestisci-notifiche").find("span.id-notifica");
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
    for(var i = 0; i < notify.length; i++) {
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
                var html_code='<div class="card-body notifica"><h6 class="card-title mittente">Hai un nuovo ordine da <strong>'+notify[i].Mittente+'</strong></h6><p class="card-text"><span class="id-notifica" hidden>'+notify[i].ID+'</span><ul><li><span class="prodotti"><strong>Numero Prodotti: </strong>'+nProd.count+'</span></li><li><span class="desc"><strong>Descrizione: </strong>'+notify[i].Descrizione+'</span></li><li><span class="luogo"><strong>Luogo Consegna: </strong></span>'+order[0].LuogoConsegna+'</li><li><span class="ora"><strong>Ora Consegna: </strong>'+order[0].Ora.slice(0,5)+'</span></li></p></ul><div class="text-right"><button class="btn btn-primary btn-sm letta" type="button">Segnala come letta</button></div></div>';
                $("form#gestisci-notifiche").html(html_code);
          }});
        }
      }});
    }
  });
}

function updateNotifyNum() {
  $.getJSON("../PHP/supplier_home.php?request=controllo-notifiche", function(data) {
    if(data.status == 'true') {
      //Inserire simbolo rosso di fianco a notifica. --> Da eliminare solo alla pressione.
      $("#numero-notifiche").html('<span class="badge badge-danger">'+data.count+'</span>');
    } else {
      $("#numero-notifiche").empty();
    }
  });
}
