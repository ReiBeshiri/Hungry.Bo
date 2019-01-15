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

          $stmt->execute();

          $result = $stmt->get_result();

          $output = array();
          while($row = $result->fetch_assoc()){
              $output[] = $row;
          }
          $stmt->close();

          print json_encode($output);

        break;

        case 'fornitori':

          $stmt = $mysqli->prepare("SELECT Username,Indirizzo,NomeLocale FROM Fornitore");

          $stmt->execute();

          $result = $stmt->get_result();

          $output = array();
          while($row = $result->fetch_assoc()){
              $output[] = $row;
          }
          $stmt->close();

          print json_encode($output);

        break;

        case 'tipolocale':

          $stmt = $mysqli->prepare("SELECT * FROM TipologiaLocale");

          $stmt->execute();

          $result = $stmt->get_result();

          $output = array();
          while($row = $result->fetch_assoc()){
              $output[] = $row;
          }
          $stmt->close();

          print json_encode($output);

        break;

        case 'tipoprodotti':

          $stmt = $mysqli->prepare("SELECT * FROM TipologiaProdotto");

          $stmt->execute();

          $result = $stmt->get_result();

          $output = array();
          while($row = $result->fetch_assoc()){
              $output[] = $row;
          }
          $stmt->close();

          print json_encode($output);

        break;

      }

      $mysqli->close();
  }
?>
