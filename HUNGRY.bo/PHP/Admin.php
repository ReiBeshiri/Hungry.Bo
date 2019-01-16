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

          $stmt = $mysqli->prepare("SELECT Username,Indirizzo,NomeLocale FROM Fornitore");

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

            $usr = $_POST["username"];
            if($_POST["table"] == "Fornitori"){
              $table = "Fornitore";
            } else {
              $table = "Cliente";
            }

            $stmt = $mysqli->prepare("DELETE FROM $table WHERE Username='$usr'");

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

      /*case 'aggiungi':

      break;*/

      $mysqli->close();
  }
?>
