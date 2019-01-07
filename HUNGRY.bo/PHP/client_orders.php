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

      $usernameClient = $_SESSION['username'];

      switch ($_GET["request"]) {

        case 'orders':

          $stmt = $mysqli->prepare("SELECT * FROM Ordine WHERE UsernameCliente = ?");

          $stmt->bind_param('s', $usernameClient);

          $stmt->execute();

          $result = $stmt->get_result();

          $ordersData = array();
          while($row = $result->fetch_assoc()){
              $ordersData[] = $row;
          }
          $stmt->close();

          /*
          array(1) {
            [0]=>
            array(5) {
              ["ID"]=>
              int(1)
              ["Stato"]=>
              string(18) "Non ancora spedito"
              ["UsernameCliente"]=>
              string(6) "mattia"
              ["LuogoConsegna"]=>
              string(5) "atrio"
              ["UsernameFornitore"]=>
              string(9) "hamburger"
            }
          }
          */

          //var_dump($ordersData[i]["CAMPO"]);

          //unset $result per evitare problemi
          $productsInOrdersData = array();
          unset($result);

          if(count($ordersData) > 0){
            for ($i = 0; $i < count($ordersData); $i++) {

              $stmt = $mysqli->prepare("SELECT * FROM ProdottoInOrdine WHERE IDOrdine = ?");

              $stmt->bind_param('i', $ordersData[$i]["ID"]);

              $stmt->execute();

              $result = $stmt->get_result();

              while($row = $result->fetch_assoc()){
                  $productsInOrdersData[] = $row;
              }
              $stmt->close();
            }

            //var_dump($productInOrdersData[0]["CAMPO"]);  $productInOrdersData[0]["IDProdotto"];
            /*
            array(1) {
              [i]=>
              array(5) {
                ["IDProdotto"]=>
                int(35)
                ["IDOrdine"]=>
                int(1)
                ["ID"]=>
                int(3)
                ["qnta"]=>
                int(1)
                ["Descrizione"]=>
                string(17) "Pizza senza farro"
              }
            }
            */
          }

          $productsData = array();
          unset($result);
          if(count($productsInOrdersData) > 0){
            for ($i = 0; $i < count($productsInOrdersData); $i++) {

              $stmt = $mysqli->prepare("SELECT * FROM Prodotto WHERE ID = ?");

              $stmt->bind_param('i', $productsInOrdersData[$i]["IDProdotto"]);

              $stmt->execute();

              $result = $stmt->get_result();

              while($row = $result->fetch_assoc()){
                  $productsData[] = $row;
              }
              $stmt->close();
            }
            /*
            array(1) {
              [i]=>
              array(7) {
                ["ID"]=>
                int(35)
                ["Nome"]=>
                string(5) "pizza"
                ["Prezzo"]=>
                int(10)
                ["TempoPreparazione"]=>
                int(15)
                ["Ingredienti"]=>
                string(20) "pomodoro, mozzarella"
                ["TipoProdotto"]=>
                string(12) "primo piatto"
                ["UsernameFornitore"]=>
                string(9) "hamburger"
              }
            }
            */
          }

          $deliveryData = array();
          unset($result);
          if(count($ordersData) > 0){
            for ($i = 0; $i < count($ordersData); $i++) {

              $stmt = $mysqli->prepare("SELECT Username, TempoArrivoCampus FROM Fornitore WHERE Username = ?");

              $stmt->bind_param('s', $ordersData[$i]["UsernameFornitore"]);

              $stmt->execute();

              $result = $stmt->get_result();

              while($row = $result->fetch_assoc()){
                  $deliveryData[] = $row;
              }
              $stmt->close();
            }
          }

          //var_dump($ordersData);
          //var_dump($productsInOrdersData);
          //var_dump($productsData);
          //var_dump($deliveryData);

          $response = array();
          array_push($response, $ordersData, $productsInOrdersData, $productsData, $deliveryData);

          //$response_array['status'] = "success";
          print json_encode($response);

        break;//case orders


        case 'details':

          if(!isset($_POST["id"])) {die();}

          $idordine = (int) $_POST["id"];
          $response_array = array();

          $stmt = $mysqli->prepare("SELECT UsernameFornitore FROM Ordine WHERE ID = ?");

          $stmt->bind_param('i', $idordine);

          $stmt->execute();

          $result = $stmt->get_result();

          $ordersData = array();
          while($row = $result->fetch_assoc()){
              $ordersData[] = $row;
          }
          $stmt->close();

          array_push($response_array, $ordersData[0]["UsernameFornitore"]);

          //seconda query
          unset($result);

          $stmt = $mysqli->prepare("SELECT IDProdotto,Qnta,Descrizione FROM ProdottoInOrdine WHERE IDOrdine = ?");

          $stmt->bind_param('i', $idordine);

          $stmt->execute();

          $result = $stmt->get_result();

          $productsInOrdersData = array();
          while($row = $result->fetch_assoc()){
              $productsInOrdersData[] = $row;
          }
          $stmt->close();

          $productsData = array();
          unset($result);

          for ($i=0; $i < count($productsInOrdersData); $i++) {
            $stmt = $mysqli->prepare("SELECT Nome FROM Prodotto WHERE ID = ?");

            $stmt->bind_param('i', $productsInOrdersData[$i]["IDProdotto"]);

            $stmt->execute();

            $result = $stmt->get_result();

            while($row = $result->fetch_assoc()){
                $productsData[] = $row;
            }
            $stmt->close();

            array_push($productsInOrdersData[$i], $productsData[$i]["Nome"]);
            array_push($response_array, $productsInOrdersData[$i]);

          }

          //var_dump($response_array);
          //[0] -> UsernameFornitore [1-array.length]->prodotti nell'Ordine dove [i]["Qnta"] [i]["Descrizione"] [i][0]->nome pietanza

          //$response_array['status'] = "success";
          print json_encode($response_array);

        break;

    }
      $mysqli->close();

  }
  ?>
