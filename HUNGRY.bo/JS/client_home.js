var nomeLocale = "";
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

  $.getJSON("../PHP/scores.php?request=voti", function(data) {

      if(data.status === "error") {

        console.log("error");

      } else{

        voti = data;
        
        $(".avg-vote").get(0).append(voti.status);

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
