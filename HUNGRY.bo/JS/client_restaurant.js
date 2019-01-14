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
  var s = url.searchParams.get("s");

  console.log(s);

  $.getJSON('../PHP/client_restaurant.php?request='+s+'', function(data) {

  });

});
