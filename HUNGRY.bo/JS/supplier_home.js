$(document).ready(function(){
  var icona;
  var immagine;

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

  $("#custom-icon").change(function(){
    icona = ($("#custom-icon").val()).split("\\");
    icona = icona[icona.length-1];
    $("#icon-label").text("Icona: "+$("#custom-icon").val());
  });

  $("#custom-img").change(function(){
    immagine = ($("#custom-img").val()).split("\\");
    immagine = immagine[immagine.length-1];
    $("#image-label").text("Immagine: "+$("#custom-img").val());
  });

  $(window).bind("resize", function () {
      if ($(this).width() < 981) {
        $("tr>td>span.ingredients-in-table").attr("hidden", "true");
      } else {
        $("tr>td>span.ingredients-in-table").removeAttr("hidden");
      }
  }).trigger('resize');

  $.getJSON("../PHP/supplier_home.php?request=informazioni-locale", function(data){
    $("span#local-name").text(data[0].NomeLocale);
    $("span#local-address").text(data[0].Indirizzo);
    $("span#local-time").text(data[0].OraApertura + "-" + data[0].OraApertura);
    $("#container>div.dumb-container>#icon-container").html('<img class="img-fluid rounded-circle local-icon" src="../res/'+data[0].Icona+'" alt="local icon"/>');
    $("#container").css("background-image", "url('../res/"+data[0].Immagine+"')");
  });

  $("button#gestisci-locale-submit").click(function(){
    event.preventDefault();

    var dataToSend = {
      icona: icona,
      immagine: immagine
    };

    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=aggiungi-immagini", dataToSend, function(data) {
      console.log(data.status);
    });
  });

});
