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

                /**********************
                OPERAZIONI ESEGUITE ALLA RIMOZIONE DI UN FORNITORE:
                1) SELEZIONA TUTTI I SUOI PRODOTTI
                2) ELIMINA TUTTI I PRODOTTI IN CARRELLO
                3) ELIMINA TUTTI I PRODOTTI
                4) PRENDE L'ID DI TUTTI GLI ORDINI IN CUI E' PRESENTE IL FORNITORE
                5) ELIMINA TUTTI I PRODOTTI IN ORDINE DEVE L'ID E' QUELLO PRESO NEL PUNTO 4
                6) ELIMINA TUTTI GLI ORDINI DI QUEL FORNITORE
                7) ELIMINA TUTTE LE RECENSIONI RELATIVE A QUEL FORNITORE
                8) ELIMINA TUTTE LE NOTIFICHE DOVE IL MITTENTE E' IL FORNITORE
                9) ELIMINA TUTTE LE NOTIFICHE DOVE IL DESTINATARIO E' IL FORNITORE
                *********************/

                $table = "Fornitore";
                ///seleziono l'id dei prodotti del fornitore
  /*1*/         $stmt = $mysqli->prepare("SELECT ID FROM Prodotto WHERE UsernameFornitore = ?");

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
/*2*/             $stmt = $mysqli->prepare("DELETE FROM ProdottoInCarrello WHERE IDProdotto = ?");

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
/*3*/             $stmt = $mysqli->prepare("DELETE FROM Prodotto WHERE ID = ?");

                  if($stmt === false){
                    $response_array['status'] = "error";
                    print json_encode($response_array);
                    die();
                  }

                  $stmt->bind_param('i', $id_prodotto[$i]["ID"]);

                  $stmt->execute();

                  $stmt->close();
                }

                //get all id from ordine
/*4*/           $stmt = $mysqli->prepare("SELECT ID FROM Ordine WHERE UsernameFornitore = ?");

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
/*5*/             $stmt = $mysqli->prepare("DELETE FROM ProdottoInOrdine WHERE IDOrdine = ?");

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
 /*6*/          $stmt = $mysqli->prepare("DELETE FROM Ordine WHERE UsernameFornitore = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();

 /*7*/          $stmt = $mysqli->prepare("DELETE FROM Recensione WHERE UsernameFornitore = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();

/*8*/           $stmt = $mysqli->prepare("DELETE FROM Notifica WHERE Mittente = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();

/*9*/           $stmt = $mysqli->prepare("DELETE FROM Notifica WHERE Destinatario = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();

              } else { //CLIENTE RIMUOVI
                /*********************
                OPERAZIONI ESEGUITE ALLA RIMOZIONE DI UN CLIENTE
                1) ELIMINA LE RECENSIONI RELATIVE A QUEL CLIENTE
                2) SELEZIONA L'ID DEGLI ORDINI RELATIVO A QUEL CLIENTE
                3) ELIMINA TUTTI I PRODOTTI IN ORDINE RELATIVI A L'ORDINE NEL PUNTO 2
                4) ELIMINO GLI ORDINI RELATIVI A QUEL CLIENTE
                5) SELEZIONO L'ID DEL CARRELLO RELATIVO A QUEL CLIENTE
                6) ELIMINO TUTTI I PRODOTTI IN CARRELLO RELATIVI ALL'ID DEL CARRELLO NEL PUNTO 5
                7) ELIMINO LE NOTIFICHE DOVE IL DESTINATARIO E' IL CLIENTE
                8) ELIMINO IL CLIENTE DALLA TABELLA DEI CLIENTI
                9) ELIMINO IL CARRELLO RELATIVO A QUEL CLIENTE
                *********************/
                $table = "Cliente";

  /*1*/         $stmt = $mysqli->prepare("DELETE FROM Recensione WHERE UsernameCliente = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();
                //get all id from ordine
  /*2*/         $stmt = $mysqli->prepare("SELECT ID FROM Ordine WHERE UsernameCliente = ?");

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
  /*3*/           $stmt = $mysqli->prepare("DELETE FROM ProdottoInOrdine WHERE IDOrdine = ?");

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
  /*4*/         $stmt = $mysqli->prepare("DELETE FROM Ordine WHERE UsernameCliente = ?");

                if($stmt === false){
                  $response_array['status'] = "error";
                  print json_encode($response_array);
                  die();
                }

                $stmt->bind_param('s', $usr);

                $stmt->execute();

                $stmt->close();

                //get id carrello
/*5*/           $stmt = $mysqli->prepare("SELECT IDCarrello FROM Cliente WHERE Username = ?");

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
/*6*/             $stmt = $mysqli->prepare("DELETE FROM ProdottoInCarrello WHERE IDCarrello = ?");

                  if($stmt === false){
                    $response_array['status'] = "error";
                    print json_encode($response_array);
                    die();
                  }

                  $stmt->bind_param('i', $id_carrello[0]["IDCarrello"]);

                  $stmt->execute();

                  $stmt->close();

              }

  /*7*/      $stmt = $mysqli->prepare("DELETE FROM Notifica WHERE Destinatario = ?");

              if($stmt === false){
                $response_array['status'] = "error";
                print json_encode($response_array);
                die();
              }

              $stmt->bind_param('s', $usr);

              $stmt->execute();

              $stmt->close();

/*8*/         $stmt = $mysqli->prepare("DELETE FROM $table WHERE Username='$usr'");

              if($stmt === false){
                $response_array['status'] = "error";
                print json_encode($response_array);
                die();
              }

              $stmt->execute();

              $stmt->close();


              //delete carrello if cli
              if($table == "Cliente"){
  /*9*/         $stmt = $mysqli->prepare("DELETE FROM Carrello WHERE ID = ?");

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
