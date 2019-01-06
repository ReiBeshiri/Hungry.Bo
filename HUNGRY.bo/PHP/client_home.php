<?php
include('functions.php');
include("db_connect.php");
header('Content-Type: application/json');

//session
sec_session_start();

if(isset($_GET['request'])) {

  //start conn
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {

      $response_array['status'] = "Errore: Connessione con il DB non riuscita";
      echo json_encode($response_array);
      die();

  }

  switch ($_GET['request']) {
    case 'categories':
      $stmt = $mysqli->prepare("SELECT * FROM TipologiaLocale");

      if($stmt == FALSE) {
        $response_array['status'] = "error";
        print json_encode($response_array);
        die();
      }

      $stmt->execute();

      $result = $stmt->get_result();

      $output = array();
      while($row = $result->fetch_assoc()){
          $output[] = $row;
      }
      $stmt->close();

      print json_encode($output);

      break;

    case 'supplier-data':
      $stmt = $mysqli->prepare("SELECT Username, NomeLocale, Immagine, Icona, TipoLocale FROM Fornitore");

      if($stmt == FALSE) {
        $response_array['status'] = "error";
        print json_encode($response_array);
        die();
      }

      $stmt->execute();

      $result = $stmt->get_result();

      $output = array();
      while($row = $result->fetch_assoc()){
          $output[] = $row;
      }
      $stmt->close();

      print json_encode($output);

      break;

    case 'local-vote':
      //get voto Fornitore
      $stmt = $mysqli->prepare("SELECT Voto FROM Recensione WHERE UsernameFornitore = ?");

      if($stmt == FALSE) {
        $response_array['status'] = "error";
        print json_encode($response_array);
        die();
      }

      $stmt->bind_param('s', $_POST['username']);

      $stmt->execute();

      $result = $stmt->get_result();

      $voto = array();

      while($row = $result->fetch_assoc()){
        $voto[] = $row;
      }

      $stmt->close();

      $tot = 0;

      if($voto == NULL){
        $avg = "-";
      } else {
        for($i = 0; $i < count($voto); $i++) {
          $tot+=$voto[$i]["Voto"];
        }
        $avg = $tot / (count($voto));
      }

      print json_encode($avg);

      break;
  }

  $mysqli->close();
}

?>
