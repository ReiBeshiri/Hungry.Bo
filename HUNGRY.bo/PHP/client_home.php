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

    case 'controllo-notifiche':
      $stmt = $mysqli->prepare("SELECT COUNT(*) FROM Notifica WHERE Destinatario = ?");

      if($stmt == false) {
        $response_array['status'] = "Errore nella query";
        print json_encode($response_array);
        die();
      }
      $stmt->bind_param('s', $_SESSION['username']);

      $stmt->execute();

      $stmt->bind_result($result);

      $stmt->fetch();

      if($result > 0) {
        $response_array['status'] = 'true';
        $response_array['count'] = $result;
      } else {
        $response_array['status'] = 'false';
      }

      print json_encode($response_array);

      break;

    case 'lista-notifiche':
      $stmt = $mysqli->prepare("SELECT * FROM Notifica WHERE Destinatario = ?");

      if($stmt == false) {
        $response_array['status'] = "Errore nella query";
        print json_encode($response_array);
        die();
      }

      $stmt->bind_param('s', $_SESSION['username']);

      $stmt->execute();

      $result = $stmt->get_result();

      $output = array();
      while($row = $result->fetch_assoc()){
          $output[] = $row;
      }
      $stmt->close();

      print json_encode($output);
      break;

    case 'ordine-notifica':
      if(isset($_POST['id'])) {
        $stmt = $mysqli->prepare("SELECT * FROM Ordine WHERE ID = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('i', $_POST['id']);

        $stmt->execute();

        $result = $stmt->get_result();

        $output = array();
        while($row = $result->fetch_assoc()){
            $output[] = $row;
        }
        $stmt->close();

        print json_encode($output);
      } else {
        $response_array['status'] = "ID mancante";
        print json_encode($response_array);
      }
      break;

    case 'rimuovi-notifica':

      if(isset($_POST['id'])) {
        $stmt = $mysqli->prepare("DELETE FROM Notifica WHERE ID=?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('i', $_POST['id']);

        $stmt->execute();

        $response_array['status'] = "success";

        print json_encode($response_array);
      } else {
        $response_array['status'] = "ID mancante";
        print json_encode($response_array);
      }
      break;

      case 'modifica-email':
        if(isset($_POST["email"])){

                  $mail = $_POST["email"];
                  $usr = $_SESSION['username'];

                  $stmt = $mysqli->prepare("UPDATE Cliente SET Email='$mail' WHERE Username='$usr'");

                  if($stmt === false){
                    $response_array['status'] = "error";
                    print json_encode($response_array);
                    die();
                  }

                  $stmt->execute();

                  $stmt->close();

                  $response_array['status'] = "success";
                  print json_encode($response_array);
                  die();
        }
      break;
  }

  $mysqli->close();
}

?>
