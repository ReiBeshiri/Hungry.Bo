$(document).ready(function(){

  $("div.alert").hide();

  $(window).bind("resize", function () {
      if ($(this).width() < 576) {
          $("#login").addClass('mx-auto');
      } else {
          $("#login").removeClass('mx-auto');
      }
  }).trigger('resize');


	$("form button").click(function() {
		  console.log("bottone premuto");
	    event.preventDefault();

	    // Crea un elemento di input che verrà usato come campo di output per la password criptata.
	    var p = document.createElement("input");

	    // Aggiungi un nuovo elemento al tuo form.
	    $("form").append(p);
	    p.name = "p";
	    p.type = "hidden"
	    p.value = hex_sha512(password.value);

	    // Assicurati che la password non venga inviata in chiaro.
	    password.value = "";

	    console.log("password hashata");

	    var dataToSend = $("form").serialize();
	    console.log(dataToSend);

	    $.post("../PHP/login.php", dataToSend, function(data) {
	        console.log(data);

	        if(!(data.status === "successclient") && !(data.status === "successsupplier")) {

	          console.log(data.status);
            $("div.alert").html("Errore: Username o password incorretti");
            $("div.alert").show();

	        } else if(data.status === "successclient"){

	          console.log("Accesso Client");
	          window.location.replace("../HTML/client_home.html");

	        } else{

	          console.log("Accesso Fornitore");
	          window.location.replace("../HTML/supplier_home.html");

	        }

      	});

	    console.log("fine post");

	});

});
