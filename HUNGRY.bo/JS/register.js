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

  $("form button").click(function() {
    //event.preventDefault();

    var errors = "";

    // Crea un elemento di input che verrà usato come campo di output per la password criptata.
    var p = document.createElement("input");

    // Aggiungi un nuovo elemento al tuo form.
    $("form").append(p);
    p.name = "p";
    p.type = "hidden"
    p.value = hex_sha512(password.value);

    // Assicurati che la password non venga inviata in chiaro.
    password.value = "";

    if (!validateEmail($("#email").val())) {
      error += "Mail non valida";
    } else if(errors.length == 0) {
      var dataToSend = $("form").serialize();
      $.post("../PHP/register.php", dataToSend, function(data) {
        console.log(data);
        if(!(data.status == "success")) {
          console.log(data.status);
        } else {
          console.log("NUOVA PAGINA");
          window.location.replace("../HTML/client_home.html");
        }
      });
    }

    //Scrivere errore su un form

  });

});

function validateEmail(email) {
  var regex = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
  return regex.test(email);
}
