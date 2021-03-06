<?php
header('Content-Type: application/json');
include("db_connect.php");

if(isset($_POST["sent"]) && isset($_POST["p"]) && isset($_POST["username"]) &&
  isset($_POST["type"]) && isset($_POST["email"])) {

  if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $response_array["status"] = "Mail non corretta";
    print json_encode($response_array);
    die();
  }

  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {
      $response_array['status'] = "Connessione con il DB non riuscita";
      print json_encode($response_array);
      die();
  }

  //Recupero tabella di Inserimento
  $user_type = ($_POST['type'] == "cliente") ? 'Cliente' : 'Fornitore';
  $username = $_POST['username'];

  if ($user_type == "Cliente") {
    //Controllo dell'esistenza di un utente con il medesimo Username.
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM Cliente WHERE Username = ?");
  } else {
    //Controllo dell'esistenza di un utente con il medesimo Username.
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM Fornitore WHERE Username = ?");
  }

  $stmt->bind_param('s', $username);

  $stmt->execute();

  $stmt->bind_result($num_users);

  $stmt->fetch();

  $stmt->close();

  if($num_users > 0) {
    $response_array['status'] = "Esiste già un utente con lo stesso nome";
    print json_encode($response_array);
  } else {
    // Recupero la password criptata dal form di inserimento.
    $password = $_POST['p'];

    $email = $_POST['email'];

    // Crea una chiave casuale
    $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

    // Crea una password usando la chiave appena creata.
    $password = hash('sha512', $password.$random_salt);

    if ($user_type == "Cliente") {
      $totale = 0.0;

      //Creazione del carrello dedicato all'utente.
      $stmt = $mysqli->prepare("INSERT INTO Carrello (Totale) VALUES (?)");

      $stmt->bind_param('d', $totale);

      $stmt->execute();

      $stmt->close();
      $stmt = $mysqli->prepare("INSERT INTO Cliente (Username, IDCarrello, Password, Email, Salt) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sisss', $username, $IDCarrello, $password, $email, $random_salt);
      $IDCarrello = $mysqli->insert_id;
    } else {
      if(!isset($_POST["tempo"]) || !isset($_POST["nome-locale"]) || !isset($_POST["indirizzo"]) ||
      !isset($_POST["tipo-locale"])) {
        $response_array['status'] = "Variabili non settate";
        print json_encode($response_array);
        die();
      }
      $stmt = $mysqli->prepare("INSERT INTO Fornitore (Username, Password, Email, Salt, TempoArrivoCampus, NomeLocale, Indirizzo, TipoLocale) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param('ssssisss', $username, $password, $email, $random_salt, $tempo, $nomeLocale, $indirizzo, $tipo);
      $tempo = $_POST["tempo"];
      $nomeLocale = $_POST["nome-locale"];
      $indirizzo = $_POST["indirizzo"];
      $tipo = $_POST["tipo-locale"];
    }

    // Esegui la query ottenuta.
    $stmt->execute();

    $response_array['status'] = "success";

    print json_encode($response_array);

  }
  $mysqli->close();
}

?>
