$(document).ready(function(){
  var cells = document.querySelectorAll(".table td");

  for (var i = 0; i < cells.length; i++) {
    cells[i].addEventListener("blur", handler);
  }

  function handler() {
  }

  $('.table-remove').click(function () {

  });

  $('.table-add').click(function () {

  });

  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
        $("tr>td>span.ingredients-in-table").attr("hidden", "true");
      } else {
        $("tr>td>span.ingredients-in-table").removeAttr("hidden");
      }
  }).trigger('resize');

  $.getJSON("../PHP/supplier_home.php?request=info-locale", function(data){
    $("span#local-name").text(data[0].NomeLocale);
    $("span#local-address").text(data[0].Indirizzo);
    $("span#local-name").text(data[0].OraApertura + "-" + data[0].OraApertura);
  });

});
