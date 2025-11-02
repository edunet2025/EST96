<?php
session_start();
require_once("conexion.php");
require_once("includes/funciones_login.php");

if (!isset($_SESSION['usuario_tmp']) || !isset($_SESSION['id_tmp'])) {
  header("Location: login.php");
  exit;
}

$usuario = $_SESSION['usuario_tmp'];
$id = $_SESSION['id_tmp'];
$tipo = $_SESSION['tipo_tmp'] ?? '';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nueva = trim($_POST['nueva'] ?? '');
  $confirmar = trim($_POST['confirmar'] ?? '');

  if ($nueva && $confirmar) {
    if ($nueva === $confirmar) {
      if (strlen($nueva) < 6) {
        $mensaje = "⚠️ La contraseña debe tener al menos 6 caracteres.";
      } else {
        $hash = password_hash($nueva, PASSWORD_BCRYPT);

        // 1️⃣ Actualizar contraseña y limpiar token
        $stmt = $conn->prepare("UPDATE usuarios 
          SET contrasena = ?, must_change_pass = 0, last_password_change = NOW(), session_token = NULL 
          WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        $stmt->execute();
        $stmt->close();

        // 2️⃣ Cerrar sesión completamente (igual que logout)
        cerrar_sesion_completa($conn, $id);

        // 3️⃣ Redirigir al login con mensaje de éxito
        header("Location: login.php?mensaje=✅ Contraseña actualizada correctamente. Inicia sesión con tu nueva contraseña.");
        exit;
      }
    } else {
      $mensaje = "❌ Las contraseñas no coinciden.";
    }
  } else {
    $mensaje = "⚠️ Llena ambos campos.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cambiar Contraseña | Técnica 96</title>
  <link rel="stylesheet" href="css/login.css">
  <style>
    .msg {
      margin-top: 1rem;
      font-weight: bold;
      color: red;
      text-align: center;
    }
    .cambiar-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: var(--beige);
    }
    .cambiar-card {
      background-color: var(--blanco);
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      text-align: center;
      width: 90%;
      max-width: 400px;
    }
    .cambiar-card h2 {
      color: var(--vino);
      margin-bottom: 0.5rem;
    }
    .cambiar-card p {
      color: var(--cafe);
      margin-bottom: 1rem;
      font-size: 0.95rem;
    }
    .cambiar-card input {
      width: 100%;
      padding: 0.7rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    .btn-guardar {
      background-color: var(--vino);
      color: var(--beige);
      border: none;
      width: 100%;
      padding: 0.8rem;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      font-size: 1rem;
      transition: 0.3s;
    }
    .btn-guardar:hover {
      background-color: var(--cafe);
    }
  </style>
</head>
<body>
  <div class="cambiar-wrapper">
    <div class="cambiar-card">
      <img src="img/logo.png" alt="Logo Escuela" class="logo-login">
      <h2>Restablecer contraseña</h2>
      <p>Hola <strong><?= htmlspecialchars($nombre) ?></strong>, por seguridad debes establecer una nueva contraseña para continuar.</p>

      <form method="POST">
        <input type="password" name="nueva" placeholder="Nueva contraseña" required>
        <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
        <button type="submit" class="btn-guardar">Guardar contraseña</button>
      </form>

      <?php if (!empty($mensaje)): ?>
        <p class="msg"><?= htmlspecialchars($mensaje) ?></p>
      <?php endif; ?>

      <a href="logout.php" class="btn-volver">Cancelar</a>
    </div>
  </div>
</body>
</html>
