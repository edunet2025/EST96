<?php
// crea un hash directamente en el mismo PHP del hosting
$password = "prueba123";
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Contraseña: $password<br>";
echo "Hash generado:<br><code>$hash</code>";
?>
