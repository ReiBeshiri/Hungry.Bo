$(document).ready(function () {

  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
          $("ul.btn-cart").empty();
          $("ul.btn-cart").html('<a href="#"> <img src="../res/cart.png" width="60" height="60" alt=""/> </a>');
          $("tr>td>span.ingredients-in-table").attr("hidden", "true");
      } else {
        $("ul.btn-cart").empty();
        $("ul.btn-cart").html('<button id="carrello" type="button" class="btn btn-outline-info">Carrello</button>');
        $("tr>td>span.ingredients-in-table").removeAttr("hidden");
      }
  }).trigger('resize');

});
