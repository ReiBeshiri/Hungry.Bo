$().ready(function () {
  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
          $("ul.btn-cart").empty();
          $("ul.btn-cart").html('<a href="#"> <img src="../res/cart.png" width="60" height="60" alt=""/> </a>');
      } else {
        $("ul.btn-cart").empty();
        $("ul.btn-cart").html('<button id="carrello" type="button" class="btn btn-outline-info">Carrello</button>');
      }
  }).trigger('resize');
});
