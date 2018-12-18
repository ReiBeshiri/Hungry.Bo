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
});
