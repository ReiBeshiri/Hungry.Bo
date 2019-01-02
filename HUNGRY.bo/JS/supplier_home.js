$(document).ready(function(){
  var icona;
  var immagine;

  $('.table-remove').click(function () {

  });

  $('.table-add').click(function () {

  });

  $.getJSON("../PHP/dbRequestManager.php?request=tipologie-prodotti", function(data){
    var html_code = "";
    for(var i = 0; i < data.length; i++){
        html_code += "<option value='"+data[i]["Nome"]+"'>"+data[i]["Nome"]+"</option>";
    }
    $("#inlineFormCustomSelect").html(html_code);
    $("#plate-type").html(html_code);
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
      if ($(this).width() < 980) {
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

  $("#gestisci-locale-submit").click(function(){
    event.preventDefault();

    var dataToSend = {
      icona: icona,
      immagine: immagine
    };

    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=aggiungi-immagini", dataToSend, function(data) {
      console.log(data.status);
      location.reload();
    });
  });

  $("form#modifica-orario button").click(function(){
    var open = $("#open");
    var close = $("#close");
    var dataToSend = $("form#modifica-orario").serialize();
    $.post("../PHP/supplier_home.php?request=modifica-orari", dataToSend, function(data){
      console.log(data);
      location.reload();
    });
  });

  $("form#inserisci-prodotto button").click(function(){
    var dataToSend = $("form#inserisci-prodotto").serialize();
    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=aggiungi-prodotto", dataToSend, function(data){
      console.log(data);
      location.reload();
    });
  });

  //Creazione dinamica delle tabelle.
  $.post("../PHP/supplier_home.php?request=lista-prodotti", function(products){
      console.log(products);
      $.getJSON("../PHP/dbRequestManager.php?request=tipologie-prodotti", function(type){
        console.log(type);
        for(var i = 0; i < type.length; i++) {
            var html_code = "";
            for(var j = 0; j < products.length; j++) {
              if(products[j].TipoProdotto === type[i].Nome) {
                  if(type[i].Nome == "Bibita") {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'</td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'€</td><td headers="modify-'+type[i].Nome+'"><span class="table-modify"><button type="button" class="btn btn-outline-info modifica" data-toggle="modal" data-target="#modify-popup">Modifica</button></span></td></tr>';
                  } else {
                    html_code += '<tr><td class="id" headers="id-'+type[i].Nome+'" hidden>'+products[j].ID+'</td><td headers="product-'+type[i].Nome+'">'+products[j].Nome+'<br/><span class="ingredients-in-table">('+products[j].Ingredienti+')</span></td><td headers="price-'+type[i].Nome+'">'+products[j].Prezzo+'€</td><td headers="modify-'+type[i].Nome+'"><span class="table-modify"><button type="button" class="btn btn-outline-info modifica" data-toggle="modal" data-target="#modify-popup">Modifica</button></span></td></tr>';
                  }
              }
            }
            $("table#"+type[i].Nome+">tbody").html(html_code);
        }
      });
  });

  //Pressione bottone modifica
  //N.B: In questo modo attacco l'evento al tbody già presente e così verrà visualizzato e ancorato anche ad
  //elementi creati dinamicamente.
  $("tbody").on('click', 'button.modifica', function(){
    var id_sel = $(this).parents("tr").children("td.id").text();
    var dataToSend = {
      id: id_sel
    };
    $.post("../PHP/dbRequestManager.php?request=seleziona-prodotto", dataToSend, function(data){
      console.log(data);
      $("input#id").val(data[0].ID);
      $("input#enter-name-prod").val(data[0].Nome);
      $("textarea#insert-ingredients-prod").val(data[0].Ingredienti);
      $("input#insert-price-prod").val(data[0].Prezzo);
      $("input#insert-preapare-time-prod").val(data[0].TempoPreparazione);
      $("select#plate-type option[value="+data[0].TipoProdotto+"]").attr('selected', 'selected');
    });
  });

  $("form#modifica-prodotto button#submit").click(function(){
    var dataToSend = $("form#modifica-prodotto").serialize();
    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=modifica-prodotto", dataToSend, function(data){
      console.log(data);
      location.reload();
    });
  });

  $("form#modifica-prodotto button#remove").click(function(){
    var id_sel = $("input#id").val();
    var dataToSend = {
      id: id_sel
    };
    console.log(dataToSend);
    $.post("../PHP/supplier_home.php?request=rimuovi-prodotto", dataToSend, function(data){
      console.log(data);
      location.reload();
    });
  });

  // $("button#logout").click(function() {
  //   $.getJSON("../PHP/logout.php", function(data) {
  //     //window.location.replace("../HTML/login.html");
  //   });
  // });
});
