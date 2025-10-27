<?php
require_once("../verificar_sesion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Alumno | Técnica 96</title>
</head>
<body>
  <h1>🎓 Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?> (Alumno)</h1>
  <p>Has iniciado sesión correctamente.</p>
  <a href="../logout.php">Cerrar sesión</a>
</body>
</html>
