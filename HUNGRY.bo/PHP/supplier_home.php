<?php
header('Content-Type: application/json');
include("db_connect.php");
include("functions.php");

sec_session_start();

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

      $stmt = $mysqli->prepare("SELECT * FROM Fornitore WHERE Username = ?");

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

      case 'aggiungi-immagini':
        $icona = $_POST["icona"];
        $immagine = $_POST["immagine"];
        $stmt = $mysqli->prepare("UPDATE Fornitore SET Icona=?, Immagine=? WHERE Username=?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('sss', $icona, $immagine, $_SESSION['username']);

        $stmt->execute();

        $stmt->close();

        $response_array['status'] = 'success';

        print json_encode($response_array);

        break;

      case 'modifica-orari':
        $apertura = $_POST["open"];
        $chiusura = $_POST["close"];
        
        $stmt = $mysqli->prepare("UPDATE Fornitore SET OraApertura=?, OraChiusura=? WHERE Username=?");

        if($stmt == false) {
          $response_array['status'] = "Errore nella query";
          print json_encode($response_array);
          die();
        }

        $stmt->bind_param('sss', $apertura, $chiusura, $_SESSION['username']);

        $stmt->execute();

        $stmt->close();

        $response_array['status'] = 'success';

        print json_encode($response_array);

        break;
    }
    $mysqli->close();
}

?>
