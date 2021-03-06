<?php
header('Content-Type: application/json');
include("db_connect.php");
include("functions.php");

sec_session_start();

if(isset($_GET["request"])) {
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {
      $response_array['status'] = "Connessione con il DB non riuscita";
      print json_encode($response_array);
      die();
  }
  switch ($_GET["request"]) {
    case 'informazioni-locale':

      $stmt = $mysqli->prepare("SELECT * FROM Fornitore WHERE Username = ?");

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

      case 'gestisci-locale':
        if(isset($_POST["icona"])) {
          $icona = $_POST["icona"];

          $stmt = $mysqli->prepare("UPDATE Fornitore SET Icona=? WHERE Username=?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('ss', $icona, $_SESSION['username']);

          $stmt->execute();

          $stmt->close();

        }

        if(isset($_POST["immagine"])) {
          $immagine = $_POST["immagine"];
          $stmt = $mysqli->prepare("UPDATE Fornitore SET Immagine=? WHERE Username=?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('ss', $immagine, $_SESSION['username']);

          $stmt->execute();

          $stmt->close();
        }

        if($_POST["email"] != "") {
          $email = $_POST["email"];

          $stmt = $mysqli->prepare("UPDATE Fornitore SET Email=? WHERE Username=?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('ss', $email, $_SESSION['username']);

          $stmt->execute();

          $stmt->close();

        }

        $response_array['status'] = 'success';

        print json_encode($response_array);

        break;

      case 'modifica-orari':
        if(isset($_POST["open"]) && isset($_POST["close"])) {
          $apertura = $_POST["open"];
          $chiusura = $_POST["close"];

          $stmt = $mysqli->prepare("UPDATE Fornitore SET OraApertura=?, OraChiusura=? WHERE Username=?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('sss', $apertura, $chiusura, $_SESSION['username']);

          $stmt->execute();

          $stmt->close();

          $response_array['status'] = 'success';

          print json_encode($response_array);
        } else {
          $response_array['status'] = "Orari non inseriti";
          print json_encode($response_array);
        }
        break;

      case 'aggiungi-prodotto':
        $nome = $_POST["nome"];
        $ingredienti = $_POST["descrizione"];
        $tempo = $_POST["tempo-preparazione"];
        $prezzo = $_POST["prezzo"];
        $tipo = $_POST["tipologia"];
        $username = $_SESSION["username"];

        $stmt = $mysqli->prepare("INSERT INTO Prodotto (Nome, Prezzo, TempoPreparazione, Ingredienti, TipoProdotto, UsernameFornitore) VALUES (?, ?, ?, ?, ?, ?)");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('sdisss', $nome, $prezzo, $tempo, $ingredienti, $tipo, $username);

        $stmt->execute();

        $stmt->close();

        $response_array['status'] = 'success';

        print json_encode($response_array);

        break;

      case 'lista-prodotti':
        $stmt = $mysqli->prepare("SELECT * FROM Prodotto WHERE UsernameFornitore = ?");

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

      case 'modifica-prodotto':
        $nome = $_POST["nome"];
        $ingredienti = $_POST["ingredienti"];
        $tempo = $_POST["tempo-di-preparazione"];
        $prezzo = $_POST["prezzo"];
        $tipo = $_POST["tipo-prodotto"];
        $username = $_SESSION["username"];
        $id = $_POST["id"];

        $stmt = $mysqli->prepare("UPDATE Prodotto SET Nome=?, Prezzo=?, TempoPreparazione=?, Ingredienti=?, TipoProdotto=? WHERE ID=?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('sdissi', $nome, $prezzo, $tempo, $ingredienti, $tipo, $id);

        $stmt->execute();

        $stmt->close();

        $response_array['status'] = 'success';

        print json_encode($response_array);

        break;

      case 'rimuovi-prodotto':
        if(isset($_POST['id'])) {
          $stmt = $mysqli->prepare("DELETE FROM ProdottoInOrdine WHERE IDProdotto=?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('i', $_POST['id']);

          $stmt->execute();

          $stmt->close();

          $stmt = $mysqli->prepare("DELETE FROM ProdottoInCarrello WHERE IDProdotto=?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('i', $_POST['id']);

          $stmt->execute();

          $stmt->close();

          $stmt = $mysqli->prepare("DELETE FROM Prodotto WHERE ID=?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('i', $_POST['id']);

          $stmt->execute();

          $stmt->close();

          $response_array['status'] = 'success';

          print json_encode($response_array);
        } else {
          $response_array['status'] = "ID mancante";
          print json_encode($response_array);
        }
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

      case 'conta-prodotti':
        if(isset($_POST['id'])) {
          $stmt = $mysqli->prepare("SELECT COUNT(*) FROM ProdottoInOrdine WHERE IDOrdine = ?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('i', $_POST['id']);

          $stmt->execute();

          $stmt->bind_result($result);

          $stmt->fetch();

          if($result > 0) {
            $response_array['count'] = $result;
          } else {
            $response_array['status'] = 'false';
          }

          print json_encode($response_array);
        } else {
          $response_array['status'] = "ID mancante";
          print json_encode($response_array);
        }
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
    }
    $mysqli->close();
}

?>
