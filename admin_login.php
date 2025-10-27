<?php
session_start();

// Configura aquí tus credenciales de administrador
$admin_user = "admin";
$admin_pass = "12345"; // cámbialo por una contraseña segura

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $usuario = trim($_POST["usuario"]);
  $password = trim($_POST["password"]);

  if ($usuario === $admin_user && $password === $admin_pass) {
    $_SESSION["admin_logged"] = true;
    header("Location: admin_portada.php");
    exit;
  } else {
    $error = "Usuario o contraseña incorrectos.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso al Panel | Técnica 96</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { background-color: var(--beige); }
    .login-container {
      max-width: 400px;
      margin: 6rem auto;
      background-color: var(--blanco);
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      padding: 2rem;
    }
    h2 { text-align: center; color: var(--vino); margin-bottom: 1.5rem; }
    label { display: block; margin-top: 1rem; color: var(--cafe); font-weight: bold; }
    input[type=text], input[type=password] {
      width: 100%;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    button {
      width: 100%;
      margin-top: 1.5rem;
      background-color: var(--vino);
      color: var(--beige);
      border: none;
      padding: 0.8rem;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover { background-color: var(--cafe); }
    .error { text-align: center; color: red; margin-top: 1rem; }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>🔒 Acceso al Panel</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
      <label>Usuario:</label>
      <input type="text" name="usuario" required>

      <label>Contraseña:</label>
      <input type="password" name="password" required>

      <button type="submit">Ingresar</button>
    </form>
  </div>
</body>
</html>
