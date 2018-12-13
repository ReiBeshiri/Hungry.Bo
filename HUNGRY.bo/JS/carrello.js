var total;
$(document).ready(function() {

	$('input[name^="qnt"]').each(function() {
    	console.log(($(this).val()));
	});

	var s = "Total " + total;

	$("#totalcart").html(s);
	
	var table = document.getElementById('cart');

	var rowLength = table.rows.length;

	for(var i=1; i<rowLength-1; i+=1){
	  var row = table.rows[i];

	  //your code goes here, looping over every row.
	  //cells are accessed as easy

	  var cellLength = row.cells.length;
	  for(var y=0; y<cellLength; y+=1){
	  	if(y%3===1){
	  		console.log(row.cells[y].innerHTML);
	  	}
	  }
	}

});