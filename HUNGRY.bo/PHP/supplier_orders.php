<?php
header('Content-Type: application/json');
include("db_connect.php");
include("functions.php");

sec_session_start();

if(isset($_GET['request'])) {

  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {
      $response_array['status'] = "Connessione con il DB non riuscita";
      print json_encode($response_array);
      die();
  }

  switch ($_GET['request']) {
    case 'orders':
      $stmt = $mysqli->prepare("SELECT * FROM Ordine WHERE UsernameFornitore = ?");

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

    case 'details':
      if(isset($_POST['id'])) {
        $stmt = $mysqli->prepare("SELECT * FROM ProdottoInOrdine WHERE IDOrdine = ?");

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

    case 'product-name':
      if(isset($_POST['id'])) {
        $stmt = $mysqli->prepare("SELECT Nome FROM Prodotto WHERE ID = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('i', $_POST['id']);

        $stmt->execute();

        $stmt->bind_result($nome);

        $stmt->fetch();

        $response_array['status'] = 'success';

        $response_array['nome'] = $nome;

        print json_encode($response_array);
      } else {
        $response_array['status'] = "ID mancante";
        print json_encode($response_array);
      }
      break;

    case 'update-status':
      if(isset($_POST['stato']) && isset($_POST['id'])) {
        $stmt = $mysqli->prepare("UPDATE Ordine SET Stato=? WHERE ID=?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('si', $_POST['stato'], $_POST['id']);

        $stmt->execute();

        $stmt->close();

        $response_array['status'] = 'success';

        print json_encode($response_array);
      } else {
        $response_array['status'] = "ID e/o stato mancante/i";
        print json_encode($response_array);
      }
      break;

    case 'notify-client':
      if(isset($_POST['id']) && isset($_POST['destinatario']) && isset($_POST['descrizione'])) {
        $stmt = $mysqli->prepare("INSERT INTO Notifica (Descrizione, Letta, Destinatario, Mittente, IDOrdine) VALUES (?, ?, ?, ?, ?)");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }
        $letta = 0;

        $stmt->bind_param('sissi', $_POST['descrizione'], $letta, $_POST['destinatario'], $_SESSION['username'], $_POST['id']);

        $stmt->execute();

        $stmt->close();

        $response_array['status'] = 'success';

        print json_encode($response_array);
      } else {
        $response_array['status'] = "IDOrdine e/o destinatario mancante/i";
        print json_encode($response_array);
      }
      break;
  }

  $mysqli->close();
}

?>
