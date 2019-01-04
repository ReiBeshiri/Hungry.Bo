<?php
header('Content-Type: application/json');
include("db_connect.php");
include("functions.php");

sec_session_start();

if(isset($_GET['request'])) {

  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {
      $response_array['status'] = "Connessione con il DB non riuscita";
      print json_encode($response_array);
      die();
  }

  switch ($_GET['request']) {
    case 'orders':
      $stmt = $mysqli->prepare("SELECT * FROM Ordine WHERE UsernameFornitore = ?");

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
  }

  $mysqli->close();
}

?>