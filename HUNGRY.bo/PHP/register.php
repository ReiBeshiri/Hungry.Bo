<?php
header('Content-Type: application/json');
define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
define("PASSWORD", ""); // Password di accesso al DB.
define("DATABASE", "HUNGRYbo"); // Nome del database.

if(isset($_POST["sent"])) {
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
      $response_array['status'] = "Error: Connessione con il DB non riuscita";
      print json_encode($response_array);
  }

  //Recupero tabella di Inserimento
  $user_type = ($_POST['type'] == "cliente") ? 'Cliente' : 'Fornitore';
  $username = $_POST['username'];

  if ($user_type == "Cliente") {
    //Controllo dell'esistenza di un utente con il medesimo Username.
    $select_user = $mysqli->prepare("SELECT COUNT(*) FROM Cliente WHERE Username = ?");
  } else {
    //Controllo dell'esistenza di un utente con il medesimo Username.
    $select_user = $mysqli->prepare("SELECT COUNT(*) FROM Fornitore WHERE Username = ?");
  }

  $select_user->bind_param('s', $username);

  $select_user->execute();

  $select_user->bind_result($num_users);
  $select_user->fetch();

  if($num_users > 0) {
    $response_array['status'] = "Error: Esiste già un utente con lo stesso nome";
    print json_encode($response_array);
  } else {
    // Recupero la password criptata dal form di inserimento.
    $password = $_POST['p'];

    $email = $_POST['email'];

    // Crea una chiave casuale
    $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

    // Crea una password usando la chiave appena creata.
    $password = hash('sha512', $password.$random_salt);

    $totale = 0;

    //Creazione del carrello dedicato all'utente.
    $create_cart = $mysqli->prepare("INSERT INTO Carrello (Totale) VALUES (?)");

    $create_cart->bind_param('i', $totale);

    $create_cart->execute();

    $IDCarrello = $conn->lastInsertId();

    if ($user_type == "Cliente") {
      $insert_stmt = $mysqli->prepare("INSERT INTO Cliente (Username, IDCarrello, Password, Email, Salt) VALUES (?, ?, ?, ?)");
    } else {
      $insert_stmt = $mysqli->prepare("INSERT INTO Fornitore (Username, IDCarrello, Password, Email, Salt) VALUES (?, ?, ?, ?)");
    }
    $insert_stmt->bind_param('sssss', $username, $IDCarrello, $password, $email, $random_salt);

    // Esegui la query ottenuta.
    $insert_stmt->execute();

    $response_array['status'] = "success";

    print json_encode($response_array);

    $mysqli->close();
  }
}

?>
