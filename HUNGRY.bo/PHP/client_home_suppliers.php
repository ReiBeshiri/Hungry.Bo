<?php
  include('functions.php');
  include("db_connect.php");
  header('Content-Type: application/json');

  //session
  sec_session_start();

  if(isset($_GET["request"])) {

  //start conn
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

  // Check connection
  if ($mysqli->connect_error) {

      $response_array['status'] = "Errore: Connessione con il DB non riuscita";
      echo json_encode($response_array);
      die();

  }

    //get Fornitori
    $stmt = $mysqli->prepare("SELECT NomeLocale FROM Fornitore");

    $stmt->execute();

    $result = $stmt->get_result();

    $suppliers = array();
    $nameSupp = array();

    while($row = $result->fetch_assoc()){
      $suppliers[] = $row;
    }

    $stmt->close();

    if($suppliers == NULL){
      $suppliers = "-";
    }

    $response_array['status'] = $suppliers;
    print json_encode($response_array);
    die();

  } else {
    $response_array['status'] = "error";
    print json_encode($response_array);
    die();
  }
 ?>
