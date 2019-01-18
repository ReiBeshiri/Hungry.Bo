var filters = [];

$(document).ready(function () {

  $("div.alert").hide();

  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
          $("ul.btn-cart>li").empty();
          $("ul.btn-cart>li").html('<a href="./carrello.html"> <img src="../res/cart.png" width="60" height="60" alt=""/> </a>');
      } else {
        $("ul.btn-cart>li").empty();
        $("ul.btn-cart>li").html('<a href="./carrello.html"><button id="cart" type="button" class="btn btn-outline-info">Carrello</button></a>');
      }
  }).trigger('resize');

  // $.getJSON("../PHP/client_home_suppliers.php?request=suppliers", function(data) {
  //   if(data.status === "error") {
  //     console.log("error");
  //   } else{
  //
  //     suppliers = data;
  //     nomilocali = suppliers.status;
  //
  //     for (var i = 0; i < nomilocali.length; i++) {
  //       ///DOPO AVER FATTO LA GRIGLIA METTO I voti come faccio a piglia nomicolcali uffa
  //       $.post("../PHP/client_home_data.php", nomilocali[i], function(data) {
  //           if(data.status === "error") {
  //               console.log("error");
  //           } else{
  //               response = data;
  //               $("#appends").append('<div class="col-lg-4 col-md-6 mb-4 col-xl-3"><div class="card"><div class="view overlay hm-white-slight"><a href="#"><img class="img-fluid local-image" src="'+"../res/"+response.status[2]+'" alt="local imgage"/><img class="img-fluid rounded-circle icon float-left ml-3" src="../res/'+response.status[3]+'" alt="local icon"/><div class="card-body"><h6 class="card-title text-center nomilocalih6">'+response.status[0]+'</h6><p class="card-text text-muted text-center vote">Voto: <span class="avg-score">'+response.status[1]+'</span></p></div></a><div class="card-footer text-right"><small class="card-text text-muted comment"><a href="#" data-toggle="modal" data-target="#rec-popup">Scrivi una recensione</a></small></div></div></div></div>');
  //           }
  //       });
  //     }
  //   }
  // });

  $.getJSON("../PHP/client_home.php?request=supplier-data", function(data) {
    for(var i = 0; i < data.length; i++) {
      var dataToSend = {
        username: data[i].Username
      }
      $.ajax({
          url: "../PHP/client_home.php?request=local-vote",
          type: "POST",
          async: false,
          dataType: "json",
          data: dataToSend,
          success: function(vote) {
            $("#appends").append('<div class="col-lg-4 col-md-6 mb-4 col-xl-3 card-locale"><span class="tipologia" hidden>'+data[i].TipoLocale+'</span><div class="card"><div class="view overlay hm-white-slight"><a href="./client_restaurant.html?supplier='+data[i].Username+'"><img class="img-fluid local-image" src="'+"../res/"+data[i].Immagine+'" alt="local imgage"/><img class="img-fluid rounded-circle icon float-left ml-3" src="../res/'+data[i].Icona+'" alt="local icon"/><div class="card-body"><span class="username" hidden>'+data[i].Username+'</span><h6 class="card-title text-center">'+data[i].NomeLocale+'</h6><p class="card-text text-muted text-center vote">Voto: <span class="avg-score">'+vote+'</span></p></div></a><div class="card-footer text-right"><small class="card-text text-muted comment"><a class="rec-link" href="#" data-toggle="modal" data-target="#rec-popup">Scrivi una recensione</a></small></div></div></div></div>');
          }});
    }
  });

  $("#appends").on('click', 'a.rec-link', function() {
    var username = $(this).parents("div.card").find("span.username").text();
    $("span#username-popup").text(username);
  })

  $("#recensione-popup button").click(function() {
      var voto = $("#score").val();
      var desc = $("#comment").val();
      var usernameFornitore =  $("span#username-popup").text();

      var dataToSend = {
        usernameFornitore:usernameFornitore,
        desc:desc,
        voto:voto
      };

      $.post("../PHP/review.php", dataToSend, function(data) {
          console.log(data);
          if(data.status === "success") {
            console.log("recensione aggiunta");
            alert("recensione aggiunta");
          } else{
            console.log("errore");
          }
          location.reload();
      });

  });

  $.getJSON("../PHP/client_home.php?request=categories", function(data) {
    if(data.status !== 'error') {
      var html_code = "";
      for(var i = 0; i < data.length; i++) {
        html_code += '<label> <input type="checkbox" name="'+data[i].Nome+'"/>'+data[i].Nome+'</label><br/>';
      }
      $("div#filter-categories form").html(html_code);
    }
  });

  $("div#filter-categories").on('change', 'input[type="checkbox"]', function() {
    var filter = $(this).attr("name");
    if($(this).is(":checked") && $.inArray(filter, filters) == -1) {
      filters.push(filter);
    } else {
      filters.splice($.inArray(filter, filters),1);
    }

    if(filters.length == 0) {
      $("div.card-locale").each(function() {
        $(this).fadeIn("slow");
      });
    } else {
      $("div.card-locale").each(function() {
        var hidden = true;
        var tipo = $(this).find("span.tipologia").text();
        filters.forEach(function(filter) {
          if(tipo == filter) {
            hidden = false;
          }
        });
        if(hidden) {
          $(this).fadeOut("slow");
        } else {
          $(this).fadeIn("slow");
        }
      });
    }
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
    $.post("../PHP/supplier_home.php?request=rimuovi-notifica", dataToSend, function(data) {
      console.log(data);
      if(data.status == 'success') {
        span.parents("div.notifica").fadeOut("slow");
        updateNotifyNum();
      }
    });
  });
});

$("#sel-mod-mail").click(function(){
  $("button.submitmail").click(function() {
      var email = $("#new-mail").val();
      console.log(email);
      if(email.length == 0 || (email.length > 0 && validateEmail(email))) {
        var dataToSend = {
          email: email
        };
        $.post("../PHP/client_home.php?request=modifica-email", dataToSend, function(data) {
          console.log(data);
          if(data.status === "success") {
            console.log("email aggiornata");
            alert("Email aggiornata");
          } else{
            console.log("errore email aggiornata");
          }
          location.reload();
        });
      } else {
        var error = "Errore nella mail.";
        $("div.alert").html(error);
        $("div.alert").show();
      }
    });
});

function checkNotify() {
  updateNotifyNum();
  $.getJSON("../PHP/client_home.php?request=lista-notifiche", function(notify) {
    var html_code = "";
    for(var i = 0; i < notify.length; i++) {
      if(notify[i].Mittente.toUpperCase() == "ADMIN" && notify[i].IDOrdine == null) {
          html_code+='<div class="card-body notifica"><h6 style="color:red;" class="card-title mittente">Hai una notifica da <strong>'+notify[i].Mittente.toUpperCase()+'</strong></h6><p class="card-text"><span class="id-notifica" hidden>'+notify[i].ID+'</span><ul><li><span class="desc"><strong>Descrizione: </strong>'+notify[i].Descrizione+'</span></li></p></ul><div class="text-right"><button class="btn btn-primary btn-sm letta" type="button">Segnala come letta</button></div></div>';
      } else {
        //Conteggio prodotti.
        var dataToSend = {
          id: notify[i].IDOrdine
        };
        $.ajax({
            url: "../PHP/client_home.php?request=ordine-notifica",
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
    }
    $("form#gestisci-notifiche").html(html_code);
  });
}

function updateNotifyNum() {
  $.getJSON("../PHP/client_home.php?request=controllo-notifiche", function(data) {
    if(data.status == 'true') {
      //Inserire simbolo rosso di fianco a notifica. --> Da eliminare solo alla pressione.
      $("#numero-notifiche").html('<span class="badge badge-danger">'+data.count+'</span>');
    } else {
      $("#numero-notifiche").empty();
    }
  });
}


function validateEmail(email) {
  var regex = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
  return regex.test(email);
}
