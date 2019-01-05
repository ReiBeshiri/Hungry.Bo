<?php
  include('functions.php');
  header('Content-Type: application/json');
  define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
  define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
  define("PASSWORD", ""); // Password di accesso al DB.
  define("DATABASE", "HUNGRYbo"); // Nome del database.

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

    $response = array();
    array_push($response, $nomelocale, $avg);

    $response_array['status'] = $response;
    print json_encode($response_array);
    die();

  } else {
    $response_array['status'] = "error";
    print json_encode($response_array);
    die();
  }
 ?>
