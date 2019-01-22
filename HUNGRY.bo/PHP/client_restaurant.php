<?php
  header('Content-Type: application/json');
  include("db_connect.php");
  include("functions.php");

  sec_session_start();

  if(isset($_GET["request"])) {
      $s = $_GET["request"];

      $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

      // Check connection
      if ($mysqli->connect_error) {
          $response_array['status'] = "Connessione con il DB non riuscita";
          print json_encode($response_array);
          die();
      }

      switch ($_GET["request"]) {
        case 'lista-prodotti':
          if(isset($_POST["username"])) {
            $stmt = $mysqli->prepare("SELECT * FROM Prodotto WHERE UsernameFornitore = ?");

            $stmt->bind_param('s', $_POST['username']);

            $stmt->execute();

            $result = $stmt->get_result();

            $output = array();
            while($row = $result->fetch_assoc()){
                $output[] = $row;
            }
            $stmt->close();

            print json_encode($output);
          } else {
            $response_array['status'] = "username mancante";
            print json_encode($response_array);
          }

          break;

        case 'informazioni-locale':
          if(isset($_POST["username"])) {
            $stmt = $mysqli->prepare("SELECT * FROM Fornitore WHERE Username = ?");

            $stmt->bind_param('s', $_POST["username"]);

            $stmt->execute();

            $result = $stmt->get_result();

            $output = array();
            while($row = $result->fetch_assoc()){
                $output[] = $row;
            }
            $stmt->close();

            print json_encode($output);
          } else {
            $response_array['status'] = "username mancante";
            print json_encode($response_array);
          }
          break;

        case 'aggiungi-al-carrello':
          if(isset($_POST["id"]) && isset($_POST["descrizione"]) && isset($_POST["qnta"])) {
            $stmt = $mysqli->prepare("SELECT ID FROM ProdottoInCarrello ORDER BY ID DESC");

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

            $stmt = $mysqli->prepare("SELECT IDCarrello FROM Cliente WHERE Username = ?");

            if($stmt == false) {
              $response_array['status'] = "Errore nella query di selezione dell'id carrello";
              print json_encode($response_array);
              die();
            }

            $stmt->bind_param('s', $_SESSION["username"]);

            $stmt->execute();

            $stmt->bind_result($id_carrello);

            $stmt->fetch();

            $stmt->close();

            $stmt = $mysqli->prepare("INSERT INTO ProdottoInCarrello (UsernameCliente, IDProdotto, ID, qnta, Descrizione, IDCarrello) VALUES (?, ?, ?, ?, ?, ?)");

            if($stmt == false) {
              $response_array['status'] = "Errore nella query di inserimento";
              print json_encode($response_array);
              die();
            }

            $stmt->bind_param('siiisi', $_SESSION["username"], $_POST["id"], $id, $_POST["qnta"], $_POST["descrizione"], $id_carrello);

            $stmt->execute();

            $stmt->close();

            $response_array['status'] = 'success';

            print json_encode($response_array);

          } else {
            $response_array['status'] = "informazioni mancanti";
            print json_encode($response_array);
          }

          break;

        case 'recensioni-locale':
          if(isset($_POST["usr"])){
            $usr = $_POST["usr"];
            $stmt = $mysqli->prepare("SELECT Descrizione,Voto,UsernameCliente FROM Recensione WHERE UsernameFornitore = ?");

            $stmt->bind_param('s', $usr);

            $stmt->execute();

            $result = $stmt->get_result();

            $output = array();
            while($row = $result->fetch_assoc()){
                $output[] = $row;
            }
            $stmt->close();

            print json_encode($output);
            
          } else {
            $response_array['status'] = "errore nella richiesta";
            print json_encode($response_array);
          }
          break;
      }

      $mysqli->close();
  }
  ?>
