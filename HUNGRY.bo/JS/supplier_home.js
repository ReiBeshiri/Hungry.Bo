$(document).ready(function(){
  var cells = document.querySelectorAll(".table td");

  for (var i = 0; i < cells.length; i++) {
    cells[i].addEventListener("blur", handler);
  }

  function handler() {
    console.log("row left!");
  }

  $('.table-remove').click(function () {

  });

  $('.table-add').click(function () {

  });
});
