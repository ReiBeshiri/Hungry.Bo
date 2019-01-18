$(document).ready(function(){
  var selectedRadio = "Cliente";

  $("div.alert").hide();

  $(window).bind("resize", function () {
      if ($(this).width() < 576) {
          $("#login").addClass('mx-auto');
      } else {
          $("#login").removeClass('mx-auto');
      }
  }).trigger('resize');

  $("#fornitori").on("change", function(){
    selectedRadio = "";
    selectedRadio = "Fornitore";
  });

  $("#clienti").on("change", function(){
    selectedRadio = "";
    selectedRadio = "Cliente";
  });


	$("form button").click(function() {
	    event.preventDefault();

	    // Crea un elemento di input che verrà usato come campo di output per la password criptata.
	    var p = document.createElement("input");
      var r = document.createElement("input");

	    // Aggiungi un nuovo elemento al tuo form.
      $("form").append(r);
	    r.name = "selectedRadio";
	    r.type = "hidden"
	    r.value = selectedRadio;

	    $("form").append(p);
	    p.name = "p";
	    p.type = "hidden"
      if(password.value !== "admin"){
	       p.value = hex_sha512(password.value);
      } else {
        p.value = password.value;
      }
	    // Assicurati che la password non venga inviata in chiaro.
	    password.value = "";

	    var dataToSend = $("form").serialize();
	    console.log(dataToSend);

	    $.post("../PHP/login.php", dataToSend, function(data) {
	        console.log(data);

          //successadmin
          if(data.status === "successadmin"){

            window.location.replace("../HTML/Admin.html");

          }else if(data.status === "successclient"){

	          console.log("Accesso Client");
	          window.location.replace("../HTML/client_home.html");

	        } else if(data.status === "successsupplier"){

	          console.log("Accesso Fornitore");
	          window.location.replace("../HTML/supplier_home.html");

	        } else {

	          console.log(data.status);
            $("div.alert").empty();
            $("div.alert").html("Errore: Username o password incorretti");
            $("div.alert").show();

	        }
      	});

	});

});
