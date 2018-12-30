<?php
include("db_connect.php");
include("functions.php");

sec_session_start();

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
    case 'informazioni-locale':
      $local_name = $GET['nome'];

      $stmt-> $mysqli->prepare("SELECT * FROM Fornitori WHERE Username = ?");

      $stmt->bind_param('s', $_SESSION['username']);

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
