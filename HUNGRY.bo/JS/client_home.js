var nomeLocale = "";
var suppliers;
var nomilocali;
var voti = 0;
$(document).ready(function () {
  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
          $("ul.btn-cart>li").empty();
          $("ul.btn-cart>li").html('<a href="./carrello.html"> <img src="../res/cart.png" width="60" height="60" alt=""/> </a>');
      } else {
        $("ul.btn-cart>li").empty();
        $("ul.btn-cart>li").html('<a href="./carrello.html"><button id="cart" type="button" class="btn btn-outline-info">Carrello</button></a>');
      }
  }).trigger('resize');

  $.getJSON("../PHP/client_home_suppliers.php?request=suppliers", function(data) {

    if(data.status === "error") {

      console.log("error");

    } else{

      suppliers = data;
      nomilocali = suppliers.status;

      for (var i = 0; i < nomilocali.length; i++) {
        ///DOPO AVER FATTO LA GRIGLIA METTO I voti come faccio a piglia nomicolcali uffa
        $.post("../PHP/client_home_data.php", nomilocali[i], function(data) {

            if(data.status === "error") {

                console.log("error");

            } else{

                response = data;

                  $("#appends").append('<div class="col-lg-4 col-md-6 mb-4 col-xl-3"><div class="card"><div class="view overlay hm-white-slight"><a href="#"><img class="img-fluid local-image" src="'+"../res/"+response.status[2]+'" alt="local imgage"/><img class="img-fluid rounded-circle icon float-left ml-3" src="'+"../res/"+response.status[2]+'../res/icona.png" alt="local icon"/><div class="card-body"><h6 class="card-title text-center nomilocalih6">'+response.status[0]+'</h6><p class="card-text text-muted text-center vote">Voto: <span class="avg-score">'+response.status[1]+'</span></p></div></a><div class="card-footer text-right"><small class="card-text text-muted comment"><a href="#" data-toggle="modal" data-target="#rec-popup">Scrivi una recensione</a></small></div></div></div></div>');

            }

        });
      }

    }

  });

  //get nomelocale when click on "scrivi reecensione"
  $("small a").click(function() {
    nomeLocale = $("div h6").html();
    console.log(nomeLocale);
  });

  $("form button").click(function() {
      console.log("bottone premuto");
      event.preventDefault();

      var voto = $("#score").val();
      var desc = $("#comment").val();

      console.log(voto);
      console.log(desc);

      var dataToSend = {
        nomeLocale:nomeLocale,
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

      });

  });

});
