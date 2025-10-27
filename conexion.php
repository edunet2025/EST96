<?php
date_default_timezone_set('America/Mexico_City');

$host = "localhost";
$usuario = "u175565826_EST96";
$contrasena = "MaVi0606";
$basededatos = "u175565826_EST96";

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);
if ($conn->connect_error) {
  die("❌ Error de conexión: " . $conn->connect_error);
}

$conn->query("SET time_zone = '-06:00'"); // hora CDMX
?>
