$(document).ready(function(){

  $("#info-fornitori").hide();

  $(window).bind("resize", function () {
      if ($(this).width() < 576) {
          $("#login").addClass('mx-auto');
      } else {
          $("#login").removeClass('mx-auto');
      }
  }).trigger('resize');

  $("#fornitori").on("change", function(){
    $("#info-fornitori").show('slow');
  });

  $("#clienti").on("change", function(){
    $("#info-fornitori").hide('slow');
  });

});
