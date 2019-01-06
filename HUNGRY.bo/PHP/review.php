<?php
  include('functions.php');
  include("db_connect.php");
  header('Content-Type: application/json');

  //session
  sec_session_start();
  if(isset($_POST["usernameFornitore"]) && isset($_POST["desc"]) && isset($_POST["voto"])){

    $usernameFornitore = $_POST["usernameFornitore"];
    $descr = $_POST["desc"];
    $score = $_POST["voto"];
    $voto = (int)$score;

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
