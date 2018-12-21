<?php
// Inserisci in questo punto il codice per la connessione al DB e l'utilizzo delle varie funzioni.
sec_session_start();
if(login_check($mysqli) == true) {

   // Inserisci qui il contenuto delle tue pagine!

} else {
   echo 'You are not authorized to access this page, please login. <br/>';
}
?>
