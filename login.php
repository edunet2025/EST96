<?php
session_start();
require_once("conexion.php");
require_once("includes/funciones_login.php");

if (isset($_SESSION['usuario']) && isset($_SESSION['tipo'])) {
  redirigir_segun_tipo($_SESSION['tipo']);
}

$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión | Técnica 96</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <img src="img/logo.png" alt="Logo Escuela" class="logo-login">
      <h2>Escuela Secundaria Técnica N.º 96</h2>
      <p class="sub">“Miguel Alemán Valdés”</p>

      <form action="verificar_login.php" method="POST" class="formulario-login">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>

        <button type="submit" class="btn-login">Ingresar</button>
        <a href="index.php" class="btn-volver">Volver al inicio</a>
      </form>

      <?php if ($error): ?>
        <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
