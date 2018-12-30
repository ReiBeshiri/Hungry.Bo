<?php
	include('functions.php');
	header('Content-Type: application/json');
	define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
	define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
	define("PASSWORD", ""); // Password di accesso al DB.
	define("DATABASE", "HUNGRYbo"); // Nome del database.

	//e2b7e67373082a8eaef1e0350580936a4b34032f5ac3886603d6cd6bb9d780ef171910c4b17a7f579d8174120dc6b797a48375553bf1f4d566d41b1efd5e5a64
	//15b97f099330b092ad1c3586f28499b2b88df7dc4ecf14a364339e7f2eb8d569ad850f2b5d6a3b124fc310bdefcdc0881c0d6d78027087dec62c97b7c94340e1
	//$password = hash('sha512', $_POST['p']."15b97f099330b092ad1c3586f28499b2b88df7dc4ecf14a364339e7f2eb8d569ad850f2b5d6a3b124fc310bdefcdc0881c0d6d78027087dec62c97b7c94340e1");
	//$password = hash('sha512', $_POST["p"]); // codifica la password usando una chiave univoca.
	//var_dump($password);

	if(isset($_POST["username"]) && isset($_POST["p"])) {

		sec_session_start();

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

		//get salt
		$stmt = $mysqli->prepare("SELECT Salt FROM $table WHERE Username = ?");

		$stmt->bind_param('s', $username);

		$stmt->execute();

		$stmt->bind_result($salt);

		$stmt->fetch();

		$stmt->close();

		//get psw
		$stmt = $mysqli->prepare("SELECT Password FROM $table WHERE Username = ?");

	  $stmt->bind_param('s', $username);

		$stmt->execute();

		$stmt->bind_result($pwd);

		$stmt->fetch();

		$stmt->close();

		//encrypting psw with salt
		//$password = hash('sha512', $_POST['p'].$salt);

		if(login($username, $password, $mysqli, $pwd, $salt)){

    		$table==="Fornitore"?$response_array['status']="successsupplier":$response_array['status']="successclient";
	    	echo json_encode($response_array);

	    }else{

	    	$response_array['status'] = "Errore: username o password non corretti";
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
