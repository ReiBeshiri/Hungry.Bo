$(window).bind("resize", function () {
    if ($(this).width() <= 981) {
        $("th#password").hide();
        $("td[headers='password']").hide();
        $("th#nome-locale").hide();
        $("td[headers='nome-locale']").hide();
        $("td[headers='modify']>span").empty();
        $("td[headers='modify']>span").html('<a href="#" data-toggle="modal" data-target="#modify-popup"><img width="30px" heigth="30px" src="../res/modify-icon.png" alt="modify"/></a>');
        $("td[headers='notify']>span").empty();
        $("td[headers='notify']>span").html('<a href="#" data-toggle="modal" data-target="#notify-popup"><img width="30px" heigth="30px" src="../res/notify-icon.png" alt="notify"/></a>');
    } else {
      $("th#password").show();
      $("td[headers='password']").show();
      $("th#nome-locale").show();
      $("td[headers='nome-locale']").show();
      $("td[headers='modify']>span").empty();
      $("td[headers='modify']>span").html('<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modify-popup">Modifica</button>');
      $("td[headers='notify']>span").empty();
      $("td[headers='notify']>span").html('<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="">Notifica</button>');
    }
}).trigger('resize');
