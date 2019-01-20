<?php
  header('Content-Type: application/json');
  include("db_connect.php");
  include("functions.php");


  if(isset($_GET["request"])) {

      $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

      // Check connection
      if ($mysqli->connect_error) {
          $response_array['status'] = "Connessione con il DB non riuscita";
          print json_encode($response_array);
          die();
      }

      switch ($_GET["request"]) {

        case 'clienti':

          $stmt = $mysqli->prepare("SELECT Username,IDCarrello,Email FROM Cliente");

          if($stmt === false){
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
          die();

        break;

        case 'fornitori':

          $stmt = $mysqli->prepare("SELECT Username,Email,NomeLocale FROM Fornitore");

          if($stmt === false){
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
          die();

        break;

        case 'tipolocale':

          $stmt = $mysqli->prepare("SELECT * FROM TipologiaLocale");

          if($stmt === false){
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
          die();

        break;

        case 'tipoprodotti':

          $stmt = $mysqli->prepare("SELECT * FROM TipologiaProdotto");

          if($stmt === false){
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
          die();

        break;

        case 'luoghi':

          $stmt = $mysqli->prepare("SELECT * FROM Luogo");

          if($stmt === false){
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
          die();

        break;

        case 'modificaFornitori':

          if($_POST["tempoarrivocampus"] != ""){

                    $tac = $_POST["tempoarrivocampus"];
                    $usr = $_POST["username"];

                    $stmt = $mysqli->prepare("UPDATE Fornitore SET TempoArrivoCampus='$tac' WHERE Username='$usr'");

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

          if($_POST["nomelocale"] != ""){

                    $nl = $_POST["nomelocale"];
                    $usr = $_POST["username"];

                    $stmt = $mysqli->prepare("UPDATE Fornitore SET NomeLocale='$nl' WHERE Username='$usr'");

                    if($stmt === false){
                      $response_array['status'] = "error";
                      print json_encode($response_array);
                      die();
                    }

                    $stmt->execute();

                    $stmt->close();

                    $desc = "Nome Locale Modificato!";
                    $admin = "Admin";
                    $letta = 0;

                    $stmt = $mysqli->prepare("INSERT INTO Notifica (Descrizione, Letta, Destinatario, Mittente) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('ssss', $desc, $letta, $usr, $admin);

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

          if($_POST["email"] != ""){

                    $email = $_POST["email"];
                    $usr = $_POST["username"];

                    $stmt = $mysqli->prepare("UPDATE Fornitore SET Email='$email' WHERE Username='$usr'");

                    if($stmt === false){
                      $response_array['status'] = "error";
                      print json_encode($response_array);
                      die();
                    }

                    $stmt->execute();

                    $stmt->close();

                    $desc = "Email modificata!";
                    $admin = "Admin";
                    $letta = 0;

                    $stmt = $mysqli->prepare("INSERT INTO Notifica (Descrizione, Letta, Destinatario, Mittente) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('ssss', $desc, $letta, $usr, $admin);

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

        case 'modificaClienti':

          if($_POST["newusername"] != ""){

                    $nusr = $_POST["newusername"];
                    $usr = $_POST["username"];

                    $stmt = $mysqli->prepare("UPDATE Cliente SET Username='$nusr' WHERE Username='$usr'");

                    if($stmt === false){
                      $response_array['status'] = "error";
                      print json_encode($response_array);
                      die();
                    }

                    $stmt->execute();

                    $stmt->close();

                    $desc = "Username Modificato!";
                    $admin = "Admin";
                    $letta = 0;

                    $stmt = $mysqli->prepare("INSERT INTO Notifica (Descrizione, Letta, Destinatario, Mittente) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('ssss', $desc, $letta, $nusr, $admin);

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

          if($_POST["email"] != ""){

                    $mail = $_POST["email"];
                    $usr = $_POST["username"];

                    $stmt = $mysqli->prepare("UPDATE Cliente SET Email='$mail' WHERE Username='$usr'");

                    if($stmt === false){
                      $response_array['status'] = "error";
                      print json_encode($response_array);
                      die();
                    }

                    $stmt->execute();

                    $stmt->close();

                    $desc = "Email aggiornata correttamente!";
                    $admin = "Admin";
                    $letta = 0;

                    $stmt = $mysqli->prepare("INSERT INTO Notifica (Descrizione, Letta, Destinatario, Mittente) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('ssss', $desc, $letta, $usr, $admin);

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

        case 'notifica':

          if($_POST["desc"] != ""){

            if($_POST["table"] == "Fornitori"){
              $table = "Fornitore";
            } else {
              $table = "Cliente";
            }

            $desc = $_POST["desc"];
            $usr = $_POST["username"];
            $admin = "Admin";
            $letta = 0;

            $stmt = $mysqli->prepare("INSERT INTO Notifica (Descrizione, Letta, Destinatario, Mittente) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $desc, $letta, $usr, $admin);

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

        case 'rimuovi':

          if($_POST["username"] != "" && $_POST["table"] != ""){

            if($_POST["table"] == "Fornitori" || $_POST["table"] == "Clienti"){

              $usr = $_POST["username"];

              if($_POST["table"] == "Fornitori"){

                $table = "Fornitore";
                ///seleziono l'id dei prodotti del fornitore
                $stmt = $mysqli->prepare("SELECT ID FROM Prodotto WHERE UsernameFornitore = ?");

                if($stmt == false) {
                  $response_array['status'] = "Errore nella di ricerca dell'ID carrello";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $result = $stmt->get_result();

                $id_prodotto = array();
                while($row = $result->fetch_assoc()){
                    $id_prodotto[] = $row;
                }
                $stmt->close();

                for ($i = 0; $i < count($id_prodotto); $i++) {
                  $stmt = $mysqli->prepare("DELETE FROM ProdottoInCarrello WHERE IDProdotto = ?");

                  if($stmt === false){
                    $response_array['status'] = "error";
                    print json_encode($response_array);
                    die();
                  }

                  $stmt->bind_param('i', $id_prodotto[$i]["ID"]);

                  $stmt->execute();

                  $stmt->close();
                }

                for ($i = 0; $i < count($id_prodotto); $i++) {
                  $stmt = $mysqli->prepare("DELETE FROM Prodotto WHERE ID = ?");

                  if($stmt === false){
                    $response_array['status'] = "error";
                    print json_encode($response_array);
                    die();
                  }

                  $stmt->bind_param('i', $id_prodotto[$i]["ID"]);

                  $stmt->execute();

                  $stmt->close();
                }

                $stmt = $mysqli->prepare("DELETE FROM Recensione WHERE UsernameFornitore = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();

              } else {
                $table = "Cliente";

                $stmt = $mysqli->prepare("DELETE FROM Recensione WHERE UsernameCliente = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();
                //get all id from ordine
                $stmt = $mysqli->prepare("SELECT ID FROM Ordine WHERE UsernameCliente = ?");

                if($stmt == false) {
                  $response_array['status'] = "Errore nella di ricerca dell'ID";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $result = $stmt->get_result();

                $id_ordine = array();
                while($row = $result->fetch_assoc()){
                    $id_ordine[] = $row;
                }
                $stmt->close();
                //delete all prodotti in ordine where idordine del cliente da eliminare
                for ($i = 0; $i < count($id_ordine); $i++) {
                  $stmt = $mysqli->prepare("DELETE FROM ProdottoInOrdine WHERE IDOrdine = ?");

                  if($stmt === false){
                    $response_array['status'] = "error";
                    print json_encode($response_array);
                    die();
                  }

                  $stmt->bind_param('i', $id_ordine[$i]["ID"]);

                  $stmt->execute();

                  $stmt->close();
                }
                //delete tutti ordini con usr cli = usr cli
                $stmt = $mysqli->prepare("DELETE FROM Ordine WHERE UsernameCliente = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();

                //get id carrello
                $stmt = $mysqli->prepare("SELECT IDCarrello FROM Cliente WHERE Username = ?");

                if($stmt == false) {
                  $response_array['status'] = "Errore nella di ricerca dell'ID";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $result = $stmt->get_result();

                $id_carrello = array();
                while($row = $result->fetch_assoc()){
                    $id_carrello[] = $row;
                }
                $stmt->close();
                //delete all prodotti in carrelo di un carrello
                  $stmt = $mysqli->prepare("DELETE FROM ProdottoInCarrello WHERE IDCarrello = ?");

                  if($stmt === false){
                    $response_array['status'] = "error";
                    print json_encode($response_array);
                    die();
                  }

                  $stmt->bind_param('i', $id_carrello[0]["IDCarrello"]);

                  $stmt->execute();

                  $stmt->close();

              }

              $stmt = $mysqli->prepare("DELETE FROM $table WHERE Username='$usr'");

              if($stmt === false){
                $response_array['status'] = "error";
                print json_encode($response_array);
                die();
              }

              $stmt->execute();

              $stmt->close();


              //delete carrello if cli
              if($table == "Cliente"){
                $stmt = $mysqli->prepare("DELETE FROM Carrello WHERE ID = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('i', $id_carrello[0]["IDCarrello"]);

                $stmt->execute();

                $stmt->close();
              }

            } else {

              $table = $_POST["table"];
              $usr = $_POST["username"];

              if($table == "Tipologie Locali"){
                $table = "TipologiaLocale";
              } else if($table == "Tipologie Prodotti") {
                $table = "TipologiaProdotto";
              } else {
                $table = "Luogo";
              }

              $stmt = $mysqli->prepare("DELETE FROM $table WHERE Nome='$usr'");

              if($stmt === false){
                $response_array['status'] = "error";
                print json_encode($response_array);
                die();
              }

              $stmt->execute();

              $stmt->close();

            }

            $response_array['status'] = "success";
            print json_encode($response_array);
            die();
          }

        break;

      case 'addtipologia':

        if($_POST["table"] != "" && $_POST["nome"] != ""){

          $nome = $_POST["nome"];
          $table = $_POST["table"];

          if($table == "Tipologie Locali"){
            $table = "TipologiaLocale";
          }else if($table == "Tipologie Prodotti"){
            $table = "TipologiaProdotto";
          } else {
            $table = "Luogo";
          }

          $stmt = $mysqli->prepare("INSERT INTO $table (Nome) VALUES (?)");

          $stmt->bind_param('s', $nome);

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

/*function notifica($username){

  $desc = "Email aggiornata!";
  $usr = $username;
  $admin = "Admin";
  $letta = 0;

  $stmt = $mysqli->prepare("INSERT INTO Notifica (Descrizione, Letta, Destinatario, Mittente) VALUES (?, ?, ?, ?)");
  $stmt->bind_param('ssss', $desc, $letta, $usr, $admin);

  if($stmt === false){
    $response_array['status'] = "error";
    print json_encode($response_array);
    die();
  }

  $stmt->execute();

  $stmt->close();
}*/
?>
