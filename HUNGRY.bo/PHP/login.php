<?php
	include("functions.php");
	header('Content-Type: application/json');
	define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
	define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
	define("PASSWORD", ""); // Password di accesso al DB.
	define("DATABASE", "HUNGRYbo"); // Nome del database.

	if(isset($_POST["username"]) && isset($_POST["p"])) {

		$username = $_POST['username'];
  	$password = $_POST['p'];
  	$table = "Cliente";

		//start conn
		$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

			// Check connection
  		if ($mysqli->connect_error) {

      		$response_array['status'] = "Errore: Connessione con il DB non riuscita";
      		echo json_encode($response_array);
      		die();

  		}

  		//check if is cliente
  		$stmt = $mysqli->prepare("SELECT COUNT(*) FROM Cliente WHERE Username = ?");

  		$stmt->bind_param('s', $username);

			$stmt->execute();

			$stmt->bind_result($num_users);

			$stmt->fetch();

			$stmt->close();

			//not client, check if supplier
		if($num_users === 0) {

				$stmt = $mysqli->prepare("SELECT COUNT(*) FROM Fornitore WHERE Username = ?");

		  	$stmt->bind_param('s', $username);

				$stmt->execute();

				$stmt->bind_result($num_users);

				$stmt->fetch();

				$stmt->close();

				if($num_users === 0) {

						//user not exsist
		      	$response_array['status'] = "Errore: utente non esistente";
		      	echo json_encode($response_array);
		      	die();

				}

				unset($table);
				$table = "Fornitore";

		}

		$stmt = $mysqli->prepare("SELECT Password FROM Cliente WHERE Username = ?");

	  $stmt->bind_param('s', $username);

		$stmt->execute();

		$stmt->bind_result($num_users);

		$stmt->fetch();

		$stmt->close();

		//if(login($username, $password, $table, $mysqli)){
		if($num_users === "ec68c1f5a144b867008b58231b9ca7c9c7d0111adc564d672987dcaa744159502d422cec491ee808d33740c993ade99f56b3e7b8f2747d6926272b6849f57c62"){

    		$table==="Fornitore"?$response_array['status']="successsupplier":$response_array['status']="successclient";
	    	echo json_encode($response_array);

	    }else{

	    	$response_array['status'] = "Errore: utente non esistente colpa di login()";
      	echo json_encode($response_array);
      	die();

	    }

	    	$mysqli->close();

	}else{

		$response_array['status'] = "Errore: variabili non settate";
    echo json_encode($response_array);
		die();

	}

?>
