<?php
  include('functions.php');
  header('Content-Type: application/json');
  define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
  define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
  define("PASSWORD", ""); // Password di accesso al DB.
  define("DATABASE", "HUNGRYbo"); // Nome del database.

  //session
  sec_session_start();

  var_dump($_GET);
  var_dump($_POST);
  

  if(isset($_GET["request"])) {

  //start conn
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {

      $response_array['status'] = "Errore: Connessione con il DB non riuscita";
      echo json_encode($response_array);
      die();

  }

    //get max id recensione di quel Fornitore
    $stmt = $mysqli->prepare("SELECT Voto FROM Recensione");

    $stmt->execute();

    $result = $stmt->get_result();

    $voto = array();

    while($row = $result->fetch_assoc()){
      $voto[] = $row;
    }

    $stmt->close();

    $tot = 0;

    if($voto == NULL){
      $voto = "-";
    } else {
      for($i = 1; $i <= count($voto)-1; $i++) {
        $tot+=$voto[$i]["Voto"];
      }
    }

    $avg = $tot / (count($voto)-1);

    $response_array['status'] = $avg;
    print json_encode($response_array);
    die();

  } else {
    $response_array['status'] = "error";
    print json_encode($response_array);
    die();
  }
 ?>
