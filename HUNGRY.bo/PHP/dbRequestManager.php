<?php
header('Content-Type: application/json');
include("db_connect.php");

if(isset($_GET["request"])) {
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {
      $response_array['status'] = "Connessione con il DB non riuscita";
      print json_encode($response_array);
      die();
  }
  switch ($_GET["request"]) {
    case 'tipologie-locali':
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

    case 'tipologie-prodotti':
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

    case 'seleziona-prodotto':
      $stmt = $mysqli->prepare("SELECT * FROM Prodotto WHERE ID=?");

      $id = $_POST["id"];

      $stmt->bind_param("i", $id);

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
