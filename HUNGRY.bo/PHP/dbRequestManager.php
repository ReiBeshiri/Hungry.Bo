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

    case 'icona-locale':
      $local_name = $GET['nome'];

      $stmt-> $mysqli->prepare("SELECT Icona FROM Fornitori WHERE Username = ?");

      $stmt->bind_param('s', $local_name);

      $stmt->execute();

      $stmt->bind_result($result);

      $stmt->fetch();

      $stmt->close();

      print json_encode($result);

      break;

    case 'immagine-locale':
      $local_name = $GET['nome'];

      $stmt-> $mysqli->prepare("SELECT Immagine FROM Fornitori WHERE Username = ?");

      $stmt->bind_param('s', $local_name);

      $stmt->execute();

      $stmt->bind_result($result);

      $stmt->fetch();

      $stmt->close();

      print json_encode($result);

      break;

    case 'orario-locale':
      $local_name = $GET['nome'];
      //Dovrebbe stamoare un solo record.
      $stmt-> $mysqli->prepare("SELECT OraApertura, OraChiusura FROM Fornitori WHERE Username = ?");

      $stmt->bind_param('s', $local_name);

      $stmt->execute();

      $stmt->bind_result($result);

      $stmt->fetch();

      $stmt->close();

      print json_encode($result);

      break;
  }
  $mysqli->close();
}
?>
