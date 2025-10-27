<?php
/*
==========================================
 crear_hash.php
 Generador de contraseñas cifradas (BCRYPT)
==========================================
*/

// Muestra errores por si hay alguno
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Si se envió una contraseña desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);

    if ($password === '') {
        $mensaje = "⚠️ Debes escribir una contraseña.";
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $mensaje = "✅ Hash generado correctamente:<br><br><code>$hash</code>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generar Hash | Técnica 96</title>
  <style>
    body {
      background: #f2e8d5;
      color: #3e2a1f;
      font-family: "Poppins", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .contenedor {
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      text-align: center;
      width: 90%;
      max-width: 400px;
    }
    h2 {
      color: #5a0d0d;
      margin-bottom: 1rem;
    }
    input {
      width: 100%;
      padding: 0.7rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }
    button {
      background: #5a0d0d;
      color: #f2e8d5;
      border: none;
      padding: 0.7rem 1.2rem;
      border-radius: 6px;
      margin-top: 1rem;
      cursor: pointer;
      font-weight: bold;
      font-size: 1rem;
    }
    button:hover {
      background: #3e2a1f;
    }
    code {
      display: block;
      background: #f2f2f2;
      padding: 0.8rem;
      border-radius: 6px;
      margin-top: 1rem;
      word-break: break-all;
    }
  </style>
</head>
<body>
  <div class="contenedor">
    <h2>🔐 Generar contraseña cifrada</h2>
    <form method="POST">
      <input type="text" name="password" placeholder="Escribe la contraseña..." required>
      <button type="submit">Generar Hash</button>
    </form>

    <?php if (!empty($mensaje)): ?>
      <div style="margin-top:1rem;"><?= $mensaje ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
