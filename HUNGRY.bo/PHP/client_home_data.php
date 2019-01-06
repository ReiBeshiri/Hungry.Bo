<?php
  include('functions.php');
  include("db_connect.php");
  header('Content-Type: application/json');

  //session
  sec_session_start();

  if(isset($_POST["NomeLocale"])) {

    $nomelocale = $_POST["NomeLocale"];

    //start conn
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    // Check connection
    if ($mysqli->connect_error) {

        $response_array['status'] = "Errore: Connessione con il DB non riuscita";
        echo json_encode($response_array);
        die();

    }

    $stmt = $mysqli->prepare("SELECT Username FROM Fornitore WHERE NomeLocale = ?");

    $stmt->bind_param('s', $nomelocale);

    $stmt->execute();

    $stmt->bind_result($username);

    $stmt->fetch();

    $stmt->close();

    if($username === NULL) {

        //user not exsist
        $response_array['status'] = "Errore: utente non esistente";
        echo json_encode($response_array);
        die();

    }

    //get voto Fornitore
    $stmt = $mysqli->prepare("SELECT Voto FROM Recensione WHERE UsernameFornitore = ?");

    $stmt->bind_param('s', $username);

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
      for($i = 1; $i <= count($voto)-1; $i++) {
        $tot+=$voto[$i]["Voto"];
      }

      $avg = $tot / (count($voto)-1);

    }


    $stmt = $mysqli->prepare("SELECT Immagine FROM Fornitore WHERE Username = ?");

    $stmt->bind_param('s', $username);

    $stmt->execute();

    $stmt->bind_result($immagine);

    $stmt->fetch();

    $stmt->close();

    if($immagine === NULL) {

      $immagine = "-";

    }

    $stmt = $mysqli->prepare("SELECT Icona FROM Fornitore WHERE Username = ?");

    $stmt->bind_param('s', $username);

    $stmt->execute();

    $stmt->bind_result($icona);

    $stmt->fetch();

    $stmt->close();

    if($icona === NULL) {

      $icona = "-";

    }


    $response = array();
    array_push($response, $nomelocale, $avg, $immagine, $icona);

    $response_array['status'] = $response;
    print json_encode($response_array);
    die();

  } else {
    $response_array['status'] = "error";
    print json_encode($response_array);
    die();
  }
 ?>
