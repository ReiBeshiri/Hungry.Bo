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

  $.getJSON("../PHP/suppliers.php?request=suppliers", function(data) {

    if(data.status === "error") {

      console.log("error");

    } else{

      suppliers = data;
      nomilocali = suppliers.status[0]["NomeLocale"];
      console.log(nomilocali);

    }

    $("#greed div").append('<div class="col-lg-4 col-md-6 mb-4 col-xl-3"><div class="card"><div class="view overlay hm-white-slight"><a href="#"><img class="img-fluid local-image" src="../res/pizzamargherita.jpg" alt="local imgage"/><img class="img-fluid rounded-circle icon float-left ml-3" src="../res/icona.png" alt="local icon"/><div class="card-body"><h6 class="card-title text-center">'+nomilocali+'</h6><p class="card-text text-muted text-center vote">Voto: <span class="avg-score"></span></p></div></a><div class="card-footer text-right"><small class="card-text text-muted comment"><a href="#" data-toggle="modal" data-target="#rec-popup">Scrivi una recensione</a></small></div></div></div></div>');

  });


  $.getJSON("../PHP/scores.php?request=voti", function(data) {

      if(data.status === "error") {

        console.log("error");

      } else{

        voti = data;

        $(".avg-score").get(0).append(voti.status);

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
