function formhash(form, password) {
   // Crea un elemento di input che verrà usato come campo di output per la password criptata.
   var p = document.createElement("input");
   // Aggiungi un nuovo elemento al tuo form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = hex_sha512(password.value);
   // Assicurati che la password non venga inviata in chiaro.
   password.value = "";
   // Come ultimo passaggio, esegui il 'submit' del form.
   form.submit();
}
function hex_sha512(s)    { return rstr2hex(rstr_sha512(str2rstr_utf8(s))); }

function sha512_vm_test()
{
  return hex_sha512("abc").toLowerCase() ==
    "ddaf35a193617abacc417349ae20413112e6fa4e89a97ea20a9eeee64b55d39a" +
    "2192992a274fc1a836ba3c23a3feebbd454d4423643ce80e2a9ac94fa54ca49f";
}
