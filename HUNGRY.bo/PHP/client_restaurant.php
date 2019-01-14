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

      $usernameClient = $_SESSION['username'];

      //ottieni Fornitore + data
      $stmt = $mysqli->prepare("SELECT Username,Icona,Immagine,TempoArrivoCampus,NomeLocale,OraApertura,OraChiusura FROM Fornitore WHERE NomeLocale = ?");

      $stmt->bind_param('s', $s);

      $stmt->execute();

      $result = $stmt->get_result();

      $supplierData = array();
      while($row = $result->fetch_assoc()){
          $supplierData[] = $row;
      }
      $stmt->close();

      var_dump($supplierData);

      $mysqli->close();

  }
  ?>
