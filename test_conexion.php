<?php
date_default_timezone_set('America/Mexico_City');

$host = "localhost";
$usuario = "u175565826_EST96";
$contrasena = "MaVi0606";
$basededatos = "u175565826_EST96";

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

if ($conn->connect_error) {
  die("❌ Error de conexión: " . $conn->connect_error);
} else {
  echo "✅ Conexión exitosa a la base de datos.<br>";
  $resultado = $conn->query("SHOW TABLES;");
  echo "📋 Tablas detectadas:<br><ul>";
  while ($fila = $resultado->fetch_row()) {
    echo "<li>" . htmlspecialchars($fila[0]) . "</li>";
  }
  echo "</ul>";
}
?>
