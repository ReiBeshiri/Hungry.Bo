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
    case "fornitori-in-carrello":
      $stmt = $mysqli->prepare("SELECT IDCarrello FROM Cliente WHERE Username = ?");

      if($stmt == false) {
        $response_array['status'] = "Errore nella di ricerca dell'ID carrello";
        print json_encode($response_array);
        die();
      }

      $stmt->bind_param('s', $_SESSION["username"]);

      $stmt->execute();

      $stmt->bind_result($id_carrello);

      $stmt->fetch();

      $stmt->close();

      $stmt = $mysqli->prepare("SELECT IDProdotto FROM ProdottoInCarrello WHERE UsernameCliente = ? AND IDCarrello = ?");

      if($stmt == false) {
        $response_array['status'] = "Errore nella query";
        print json_encode($response_array);
        die();
      }

      $stmt->bind_param('si', $_SESSION["username"], $id_carrello);

      $stmt->execute();

      $result = $stmt->get_result();

      $output = array();
      while($row = $result->fetch_assoc()){
          $output[] = $row;
      }
      $stmt->close();

      $suppliers = array();
      for($x = 0; $x < count($output); $x++) {
        $stmt = $mysqli->prepare("SELECT UsernameFornitore FROM Prodotto WHERE ID = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella ricerca dello username associato all'idProdotto";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('i', $output[$x]["IDProdotto"]);

        $stmt->execute();

        $stmt->bind_result($usernameFornitore);

        $stmt->fetch();

        $stmt->close();

        if(!in_array($usernameFornitore, $suppliers)) {
          $suppliers[] = $usernameFornitore;
        }
      }

      $info = array();

      for($x = 0; $x < count($suppliers); $x++) {
        $stmt = $mysqli->prepare("SELECT Username, NomeLocale, Icona FROM Fornitore WHERE Username = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella ricerca delle informazioni dei fornitori";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('s', $suppliers[$x]);

        $stmt->execute();

        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()){
            $info[] = $row;
        }
        $stmt->close();
      }

      print json_encode($info);

      break;
    case "prodotti-in-carrello":
      if(isset($_POST["usernameFornitore"])) {
        $stmt = $mysqli->prepare("SELECT IDCarrello FROM Cliente WHERE Username = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella di ricerca dell'ID carrello";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('s', $_SESSION["username"]);

        $stmt->execute();

        $stmt->bind_result($id_carrello);

        $stmt->fetch();

        $stmt->close();

        $stmt = $mysqli->prepare("SELECT * FROM ProdottoInCarrello WHERE UsernameCliente = ? AND IDCarrello = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('si', $_SESSION["username"], $id_carrello);

        $stmt->execute();

        $result = $stmt->get_result();

        $output = array();
        while($row = $result->fetch_assoc()){
            $output[] = $row;
        }

        $stmt->close();
        
        $result = array();
        for($x = 0; $x < count($output); $x++) {
          $stmt = $mysqli->prepare("SELECT COUNT(*) FROM Prodotto WHERE ID = ? AND UsernameFornitore = ?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query di ricerca del prodotto";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('is', $output[$x]["IDProdotto"], $_POST["usernameFornitore"]);

          $stmt->execute();

          $stmt->bind_result($count);

          $stmt->fetch();

          $stmt->close();

          if ($count > 0) {
            $result[] = $output[$x];
          }
        }

        print json_encode($result);
      } else {
        $response_array['status'] = "Informazioni mancanti";
        print json_encode($response_array);
      }
      break;
    case "informazioni-prodotto":
      if(isset($_POST["id"])) {
        $stmt = $mysqli->prepare("SELECT * FROM Prodotto WHERE ID = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('i', $_POST["id"]);

        $stmt->execute();

        $result = $stmt->get_result();

        $output = array();
        while($row = $result->fetch_assoc()){
            $output[] = $row;
        }
        $stmt->close();

        print json_encode($output);
      } else {
        $response_array['status'] = "Informazioni mancanti";
        print json_encode($response_array);
      }
      break;
    case "update-qnta":
      if(isset($_POST["id"]) && isset($_POST["qnta"])) {
        $stmt = $mysqli->prepare("SELECT IDCarrello FROM Cliente WHERE Username = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella di ricerca dell'ID carrello";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('s', $_SESSION["username"]);

        $stmt->execute();

        $stmt->bind_result($id_carrello);

        $stmt->fetch();

        $stmt->close();

        //Update della quantità del prodotto in carrello.
        $stmt = $mysqli->prepare("UPDATE ProdottoInCarrello SET qnta = ? WHERE UsernameCliente = ? AND IDCarrello = ? AND ID = ?");

        $stmt->bind_param('ssii', $_POST["qnta"], $_SESSION["username"], $id_carrello, $_POST["id"]);

        $stmt->execute();

        $stmt->close();

        $response_array["status"] = "success";
        print json_encode($response_array);
      } else {
        $response_array['status'] = "Informazioni mancanti";
        print json_encode($response_array);
      }
      break;
    case "rimuovi-prodotto":
      if(isset($_POST["id"])) {
        $stmt = $mysqli->prepare("DELETE FROM ProdottoInCarrello WHERE ID = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('i', $_POST["id"]);

        $stmt->execute();

        $stmt->close();

        $response_array["status"] = "success";
        print json_encode($response_array);
      } else {
        $response_array['status'] = "ID mancante";
        print json_encode($response_array);
      }
      break;
    case "luoghi-consegna":
      $stmt = $mysqli->prepare("SELECT * FROM Luogo");

      if($stmt == false) {
        $response_array['status'] = "Errore nella query";
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
    case "ordine-effettuato":
      if(isset($_POST["username"]) && isset($_POST["ids"]) && isset($_POST["cvv"]) && isset($_POST["num"]) && isset($_POST["hour"]) && isset($_POST["place"])) {
        $stmt = $mysqli->prepare("SELECT IDCarrello FROM Cliente WHERE Username = ?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query di ricerca dell'ID carrello";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('s', $_SESSION["username"]);

        $stmt->execute();

        $stmt->bind_result($id_carrello);

        $stmt->fetch();

        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO Ordine (Stato, UsernameCliente, LuogoConsegna, UsernameFornitore, Ora) VALUES (?, ?, ?, ?, ?)");

        if($stmt == false) {
          $response_array['status'] = "Errore nella creazione dell'ordine";
          print json_encode($response_array);
          die();
        }

        $stato = "Ordinato";

        $stmt->bind_param('sssss', $stato, $_SESSION["username"], $_POST["place"], $_POST["username"], $_POST['hour']);

        $stmt->execute();

        $order_id = $mysqli->insert_id;

        $stmt->close();

        for($x = 0; $x < count($_POST['ids']); $x++) {
          $stmt = $mysqli->prepare("SELECT * FROM ProdottoInCarrello WHERE UsernameCliente = ? AND IDCarrello = ? AND ID = ?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella ricerca dei prodotti in carrello";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('sii', $_SESSION["username"], $id_carrello, $_POST['ids'][$x]);

          $stmt->execute();

          $result = $stmt->get_result();

          $p_in_cart = [];
          while($row = $result->fetch_assoc()){
              $p_in_cart[] = $row;
          }

          $stmt->close();

          $stmt = $mysqli->prepare("SELECT ID FROM ProdottoInOrdine ORDER BY ID DESC");

          if($stmt == false) {
            $response_array['status'] = "Errore nella query di selezione dell'ID";
            print json_encode($response_array);
            die();
          }

          $stmt->execute();

          $stmt->bind_result($id);

          $stmt->fetch();

          $stmt->close();

          if ($id == NULL) {
            $id = 1;
          } else {
            $id = $id + 1;
          }

          $stmt = $mysqli->prepare("INSERT INTO ProdottoInOrdine (IDProdotto, IDOrdine, ID, qnta, Descrizione) VALUES (?, ?, ?, ?, ?)");

          if($stmt == false) {
            $response_array['status'] = "Errore nella ricerca dei prodotti in carrello";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('iiiis', $p_in_cart[0]["IDProdotto"], $order_id, $id, $p_in_cart[0]["qnta"], $p_in_cart[0]["Descrizione"]);

          $stmt->execute();

          $stmt->close();

          $stmt = $mysqli->prepare("DELETE FROM ProdottoInCarrello WHERE UsernameCliente = ? AND IDCarrello = ? AND ID = ?");

          if($stmt == false) {
            $response_array['status'] = "Errore nella ricerca dei prodotti in carrello";
            print json_encode($response_array);
            die();
          }

          $stmt->bind_param('sii', $_SESSION["username"], $id_carrello, $_POST['ids'][$x]);

          $stmt->execute();

          $stmt->close();

        }

        //Creazione notifica
        $stmt = $mysqli->prepare("INSERT INTO Notifica (Letta, Destinatario, Mittente, IDOrdine) VALUES (?, ?, ?, ?)");

        if($stmt == false) {
          $response_array['status'] = "Errore nella creazione della notifica";
          print json_encode($response_array);
          die();
        }

        $letta = 0;

        $stmt->bind_param('issi', $letta, $_POST["username"], $_SESSION["username"], $order_id);

        $stmt->execute();

        $stmt->close();

        $response_array['status'] = "success";
        print json_encode($response_array);
      } else {
        $response_array['status'] = "Informazioni mancanti";
        print json_encode($response_array);
      }
      break;
  }

  $mysqli->close();
}
?>
