$(document).ready(function(){

  $(window).bind("resize", function () {
    if($(this).width() <= 320){

    } else if ($(this).width() <= 981) {
      $("th#password").hide();
      $("td[headers='indirizzo']").hide();
      $("th#indirizzo").hide();
      $("th#nome-locale").hide();
      $("td[headers='nome-locale']").hide();
      $("td[headers='modify']>span").empty();
      $("td[headers='modify']>span").html('<a href="#"><img width="30px" heigth="30px" src="../res/modify-icon.png" alt="modify" data-toggle="modal" data-target="#modify-from-admin"/></a>');
      $("td[headers='notify']>span").empty();
      $("td[headers='notify']>span").html('<a href="#"><img width="30px" heigth="30px" src="../res/notify-icon.png" alt="notify" data-toggle="modal" data-target="#send-notify-from-admin"/></a>');
    } else {
      $("th#password").show();
      $("td[headers='indirizzo']").show();
      $("th#indirizzo").show();
      $("th#nome-locale").show();
      $("td[headers='nome-locale']").show();
      $("td[headers='modify']>span").empty();
      $("td[headers='modify']>span").html('<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modify-from-admin">Modify</button>');
      $("td[headers='notify']>span").empty();
      $("td[headers='notify']>span").html('<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#send-notify-from-admin">Notify</button>');
    }
  }).trigger('resize');

  $("#manage_cli").click(function() {
    managecli();
  });

  $("#manage_sup").click(function() {
    managesup();
  });

  $("#local-type").click(function() {
    localtype();
  });

  $("#prod-type").click(function() {
    prodtype();
  });

  $("#delivery-place").click(function() {
    deliveryplace();
  });

  $("div.admintable").on('click', 'button.buttonModify', function() {
    var username = $(this).parents("tr").children("td[headers='username']").text();
    $("button.save").click(function() {
      var table = $("strong.searchtable").text();
      var dataToSend = {
        username: username,
        email: $("#enter-mail").val(),
        nomelocale: $("#enter-name").val(),
        tempoarrivocampus: $("#enter-time").val(),
      };
      console.log(dataToSend);
      $.post("../PHP/Admin.php?request=modificaFornitori", dataToSend, function(data) {
        if(data.status === "success") {
          console.log("Modifica Completata");
          alert("Modifica Completata");
        } else {
          console.log("errore");
          alert("Errore durante la Modifica");
        }
      });
      location.reload();
    });
  });


  $("div.admintable").on('click', 'button.buttonModifycli', function() {
    var username = $(this).parents("tr").children("td[headers='username']").text();
    $("button.savecli").click(function() {
      var table = $("strong.searchtable").text();
      var dataToSend = {
        username: username,
        email: $("#enter-mail-cli").val(),
        newusername: $("#enter-username-cli").val(),
      };
      console.log(dataToSend);
      $.post("../PHP/Admin.php?request=modificaClienti", dataToSend, function(data) {
        if(data.status === "success") {
          console.log("Modifica Completata");
          alert("Modifica Completata");
        } else {
          console.log("errore");
          alert("Errore durante la Modifica");
        }
      });
      location.reload();
    });
  });

  $("div.admintable").on('click', 'button.buttonNotify', function() {
    var username = $(this).parents("tr").children("td[headers='username']").text();
    $("button.notificaUser").click(function() {
    var table = $("strong.searchtable").text();
    var dataToSend = {
      username: username,
      table: $("strong.searchtable").text(),
      desc: $("#insert-notify-desc").val(),
    };
      $.post("../PHP/Admin.php?request=notifica", dataToSend, function(data) {
        if(data.status === "success") {
          console.log("Notifica Completata");
          alert("Notifica Completata");
        } else {
          console.log("errore");
          alert("Errore durante la Notifica");
        }
      });
      location.reload();
    });
  });

  $("div.admintable").on('click', 'button.buttonAdd', function() {
    var table = $("strong.searchtable").text();
    if(table === "Fornitori"){
      $("input#tipoAgg").hide();
      $("label#tipologia").hide();
      $("input#usrNomeLocale").show();
      $("label#usrNomeLocalelbl").show();
      $("input#usrInd").show();
      $("label#usrIndlbl").show();
      $("input#usrTac").show();
      $("label#usrTaclbl").show();
      $("select#usrTipo").show();
      $("label#usrTipolbl").show();
      $("input#usrNomeLocale").attr("required", "true");
      $("input#usrInd").attr("required", "true");
      $("input#usrTipo").attr("required", "true");
      $("input#usrTac").attr("required", "true");
      $.getJSON("../PHP/dbRequestManager.php?request=tipologie-locali", function(data){
        var html_code = "";
        for(var i = 0; i < data.length; i++){
            html_code += "<option value='"+data[i]["Nome"]+"'>"+data[i]["Nome"]+"</option>";
        }
        $("form select").html(html_code);
      });
    } else if(table === "Clienti"){
      $("input#tipoAgg").hide();
      $("label#tipologia").hide();
      $("input#usrNomeLocale").hide();
      $("label#usrNomeLocalelbl").hide();
      $("input#usrInd").hide();
      $("label#usrIndlbl").hide();
      $("input#usrTac").hide();
      $("label#usrTaclbl").hide();
      $("input#usrTipo").hide();
      $("label#usrTipolbl").hide();
      $("input#usrNomeLocale").removeAttr("required");
      $("input#usrInd").removeAttr("required");
      $("input#usrTipo").removeAttr("required");
      $("input#usrTac").removeAttr("required");
      $("input#usrUsr").attr("required", "true");
      $("input#usrPwd").attr("required", "true");
      $("input#usrEmail").attr("required", "true");
    } else {
      $("input#tipoAgg").show();
      $("label#tipologia").show();
      $("input#usrUsr").hide();
      $("label#usrlbl").hide();
      $("input#usrPwd").hide();
      $("label#pwdlbl").hide();
      $("input#usrEmail").hide();
      $("label#emaillbl").hide();
      $("input#usrNomeLocale").hide();
      $("label#usrNomeLocalelbl").hide();
      $("input#usrInd").hide();
      $("label#usrIndlbl").hide();
      $("input#usrTac").hide();
      $("label#usrTaclbl").hide();
      $("input#usrTipo").hide();
      $("label#usrTipolbl").hide();
      $("input#usrNomeLocale").removeAttr("required");
      $("input#usrInd").removeAttr("required");
      $("input#usrTipo").removeAttr("required");
      $("input#usrTac").removeAttr("required");
      $("input#usrUsr").removeAttr("required");
      $("input#usrPwd").removeAttr("required");
      $("input#usrEmail").removeAttr("required");
    }
    $("button.addUser").click(function() {
      if(table === "Fornitori" || table === "Clienti"){
          if(table === "Fornitori"){
            var dataToSend = {
              sent: "true",
              type: "fornitore",
              username: $("#usrUsr").val(),
              p: $("#usrPwd").val(),
              email: $("#usrEmail").val(),
              'nome-locale': $("#usrNomeLocale").val(),
              indirizzo: $("#usrInd").val(),
              tempo: $("#usrTac").val(),
              'tipo-locale': $("#usrTipo").val(),
            };
          } else if(table === "Clienti"){
            var dataToSend = {
              sent: "true",
              type: "cliente",
              username: $("#usrUsr").val(),
              p: $("#usrPwd").val(),
              email: $("#usrEmail").val(),
            };
          }
          $.post("../PHP/register.php", dataToSend, function(data) {
            if(data.status === "success") {
              console.log("Aggiunta Utente Completata");
              alert("Aggiunta Utente Completata");
            } else {
              console.log("errore");
              alert("Errore durante l'aggiunta Utente");
            }
          });
      } else if(table === "Tipologie Locali" || table === "Tipologie Prodotti" || table === "Luoghi di Consegna"){
        console.log(table);
            var dataToSend = {
              table: $("strong.searchtable").text(),
              nome: $("input#tipoAgg").val(),
            };
            $.post("../PHP/Admin.php?request=addtipologia", dataToSend, function(data) {
              if(data.status === "success") {
                console.log("Aggiunta prodotto Completata");
                alert("Aggiunta prodotto Completata");
              } else {
                console.log("errore");
                alert("Errore durante la Aggiunta prodotto");
              }
            });
      }
      location.reload();
    });
  });

  $("div.admintable").on('click', 'a.aremove', function() {
    var username = $(this).parents("tr").children("td[headers='username']").text();
    var table = $("strong.searchtable").text();
    $("button.deleteUser").click(function() {
      console.log($("strong.searchtable").text());
         var dataToSend = {
           table: table,
           username: username,
         };
         $.post("../PHP/Admin.php?request=rimuovi", dataToSend, function(data) {
           if(data.status === "success") {
             console.log("Rimozione Completata");
             //alert("Rimozione Completata");
           } else {
             console.log("errore");
            // alert("Errore durante la Rimozione");
           }
         });
         location.reload();
    });
  });
});


function prodtype() {
  $.getJSON("../PHP/Admin.php?request=tipoprodotti", function(data) {
    if(data !== null){
      $("div.admintable").empty();
      var str = "";
      var str1 = '<div class="fluid-container"><div class="row tables"><div class="text-center col-12"><div class="table-header"><p><strong class="searchtable">Tipologie Prodotti</strong></p></div><table class="table table-hover"><thead class="thead-dark"><tr><th id="username">Nome</th><th id="remove">Del</th></tr></thead><tbody>';
      var str2 = "";
      var str3 = '</tbody></table><button type="button" class="btn btn-outline-success buttonAdd" data-toggle="modal" data-target="#add-from-admin">Aggiungi</button></div></div></div>';
      for (var i = 0; i < data.length; i++) {
        str2 += '<tr><td headers="username">'+data[i]["Nome"]+'</td><td headers="remove"><a data-toggle="modal" data-target="#confirm-delete-admin" class="aremove"><input class="cancel" name="cancel" type="image" src="../res/croce.png" alt="delete"/></a></td></tr>';
      }
      str = str1+str2+str3;
      $("div.admintable").append(str)
    }
  });
}

function localtype(){
  $.getJSON("../PHP/Admin.php?request=tipolocale", function(data) {
    if(data !== null){
      $("div.admintable").empty();
      var str = "";
      var str1 = '<div class="fluid-container"><div class="row tables"><div class="text-center col-12"><div class="table-header"><p><strong class="searchtable">Tipologie Locali</strong></p></div><table class="table table-hover"><thead class="thead-dark"><tr><th id="username">Nome</th><th id="remove">Del</th></tr></thead><tbody>';
      var str2 = "";
      var str3 = '</tbody></table><button type="button" class="btn btn-outline-success buttonAdd" data-toggle="modal" data-target="#add-from-admin">Aggiungi</button></div></div></div>';
      for (var i = 0; i < data.length; i++) {
        str2 += '<tr><td headers="username">'+data[i]["Nome"]+'</td><td headers="remove"><a data-toggle="modal" data-target="#confirm-delete-admin" class="aremove"><input class="cancel" name="cancel" type="image" src="../res/croce.png" alt="delete"/></a></td></tr>';
      }
      str = str1+str2+str3;
      $("div.admintable").append(str)
    }
  });
}

function managesup(){
  $.getJSON("../PHP/Admin.php?request=fornitori", function(data) {
    if(data !== null){
      $("div.admintable").empty();
      var str = "";
      var str1 = '<div class="fluid-container"><div class="row tables"><div class="text-center col-12"><div class="table-header"><p><strong class="searchtable">Fornitori</strong></p></div><table class="table table-hover"><thead class="thead-dark"><tr><th id="username">Username</th><th id="indirizzo">Email</th><th id="nome-locale">NomeLocale</th><th id="modify">Modify</th><th id="notify">Notify</th><th id="remove" hidden>Remove</th></tr></thead><tbody>';
      var str2 = "";
      var str3 = '</tbody></table><button type="button" class="btn btn-outline-success buttonAdd" data-toggle="modal" data-target="#add-from-admin">Aggiungi</button></div></div></div>';
      for (var i = 0; i < data.length; i++) {
        str2 += '<tr><td headers="username">'+data[i]["Username"]+'</td><td headers="indirizzo">'+data[i]["Email"]+'</td><td headers="nome-locale">'+data[i]["NomeLocale"]+'</td><td headers="modify"><span class="table-modify"><button type="button" class="btn btn-outline-info buttonModify" data-toggle="modal" data-target="#modify-from-admin">Modify</button></span></td><td headers="notify"><span class="table-modify"><button type="button" class="btn btn-outline-primary buttonNotify" data-toggle="modal" data-target="#send-notify-from-admin">Notify</button></span></td><td headers="remove"><a data-toggle="modal" data-target="#confirm-delete-admin" class="aremove"><input class="cancel" name="cancel" type="image" src="../res/croce.png" alt="delete"/></a></td></tr>';
      }
      str = str1+str2+str3;
      $("div.admintable").append(str)
    }
  });
}

function managecli(){
  $.getJSON("../PHP/Admin.php?request=clienti", function(data) {
    if(data !== null){
      $("div.admintable").empty();
      var str = "";
      var str1 = '<div class="fluid-container"><div class="row tables"><div class="text-center col-12"><div class="table-header"><p><strong class="searchtable">Clienti</strong></p></div><table class="table table-hover"><thead class="thead-dark"><tr><th id="username">Username</th><th id="indirizzo">IDCarrello</th><th id="nome-locale">Email</th><th id="modify">Modify</th><th id="notify">Notify</th><th id="remove" hidden>Remove</th></tr></thead><tbody>';
      var str2 = "";
      var str3 = '</tbody></table><button type="button" class="btn btn-outline-success buttonAdd" data-toggle="modal" data-target="#add-from-admin">Aggiungi</button></div></div></div>';
      for (var i = 0; i < data.length; i++) {
        str2 += '<tr><td headers="username">'+data[i]["Username"]+'</td><td headers="indirizzo">'+data[i]["IDCarrello"]+'</td><td headers="nome-locale">'+data[i]["Email"]+'</td><td headers="modify"><span class="table-modify"><button type="button" class="btn btn-outline-info buttonModifycli" data-toggle="modal" data-target="#modify-from-admin-cli">Modify</button></span></td><td headers="notify"><span class="table-modify"><button type="button" class="btn btn-outline-primary buttonNotify" data-toggle="modal" data-target="#send-notify-from-admin">Notify</button></span></td><td headers="remove"><a data-toggle="modal" data-target="#confirm-delete-admin" class="aremove"><input class="cancel" name="cancel" type="image" src="../res/croce.png" alt="delete"/></a></td></tr>';
      }
      str = str1+str2+str3;
      $("div.admintable").append(str)
    }
  });
}

function deliveryplace(){
  $.getJSON("../PHP/Admin.php?request=luoghi", function(data) {
    if(data !== null){
      $("div.admintable").empty();
      var str = "";
      var str1 = '<div class="fluid-container"><div class="row tables"><div class="text-center col-12"><div class="table-header"><p><strong class="searchtable">Luoghi di Consegna</strong></p></div><table class="table table-hover"><thead class="thead-dark"><tr><th id="username">Nome</th><th id="remove">Del</th></tr></thead><tbody>';
      var str2 = "";
      var str3 = '</tbody></table><button type="button" class="btn btn-outline-success buttonAdd" data-toggle="modal" data-target="#add-from-admin">Aggiungi</button></div></div></div>';
      for (var i = 0; i < data.length; i++) {
        str2 += '<tr><td headers="username">'+data[i]["Nome"]+'</td><td headers="remove"><a data-toggle="modal" data-target="#confirm-delete-admin" class="aremove"><input class="cancel" name="cancel" type="image" src="../res/croce.png" alt="delete"/></a></td></tr>';
      }
      str = str1+str2+str3;
      $("div.admintable").append(str)
    }
  });
}


/*
var url_string = window.location.href;
var url = new URL(url_string);
var supplier = url.searchParams.get("supplier");
*/
