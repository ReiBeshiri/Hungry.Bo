<?php
  include('functions.php');
  header('Content-Type: application/json');
  define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
  define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
  define("PASSWORD", ""); // Password di accesso al DB.
  define("DATABASE", "HUNGRYbo"); // Nome del database.

  if(isset($_POST["nomeLocale"]) && isset($_POST["desc"]) && isset($_POST["voto"])){

    $nomeLocaleFornitore = $_POST["nomeLocale"];
    $descr = $_POST["desc"];
    $score = $_POST["voto"];
    $voto = (int)$score;


    //session
    sec_session_start();
    //get username Cliente
    $usernameCliente = $_SESSION["username"];

    //start conn
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    // Check connection
    if ($mysqli->connect_error) {

        $response_array['status'] = "Errore: Connessione con il DB non riuscita";
        echo json_encode($response_array);
        die();

    }

    //get username Fornitore
    $stmt = $mysqli->prepare("SELECT Username FROM Fornitore WHERE NomeLocale = ?");

    $stmt->bind_param('s', $nomeLocaleFornitore);

    $stmt->execute();

    $stmt->bind_result($usernameFornitore);

    $stmt->fetch();

    $stmt->close();

    //get max id recensione di quel Fornitore
    $stmt = $mysqli->prepare("SELECT MAX(ID) FROM Recensione WHERE UsernameFornitore = ?");

    $stmt->bind_param('s', $usernameFornitore);

    $stmt->execute();

    $stmt->bind_result($maxID);

    $stmt->fetch();

    $stmt->close();

    if($maxID == NULL){
      $maxID = 1;
    } else {
      $maxID++;
    }

    $stmt = $mysqli->prepare("INSERT INTO Recensione VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param('sisis', $usernameFornitore, $maxID, $descr, $voto, $usernameCliente);

    $res = $stmt->execute();

    $stmt->close();

    if($res){

      $response_array['status'] = "success";
      print json_encode($response_array);
      die();

    } else{

      $response_array['status'] = "error";
      print json_encode($response_array);
      die();

    }

  } else{

    $response_array['status'] = "Errore: variabili non settate";
    echo json_encode($response_array);
    die();

  }

?>
