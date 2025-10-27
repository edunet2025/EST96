<?php
session_start();
if (empty($_SESSION["admin_logged"])) {
  header("Location: admin_login.php");
  exit;
}

$dataFile = "data/portada.json";
$uploadDir = "uploads/";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $titulo = trim($_POST["titulo"]);
  $descripcion = trim($_POST["descripcion"]);
  $imagen = "";

  if (!empty($_FILES["imagen"]["name"])) {
    $fileName = "portada_" . time() . "_" . basename($_FILES["imagen"]["name"]);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $targetFile)) {
      $imagen = $targetFile;
    }
  }

  $oldData = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
  if (empty($imagen) && !empty($oldData["imagen"])) {
    $imagen = $oldData["imagen"];
  }

  $data = [
    "titulo" => $titulo,
    "descripcion" => $descripcion,
    "imagen" => $imagen
  ];
  file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  $mensaje = "✅ Portada actualizada correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualizar Portada del Mes | Técnica 96</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { background-color: var(--beige); }
    .admin-container {
      max-width: 700px;
      margin: 3rem auto;
      background-color: var(--blanco);
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .top-bar {
      display: flex; justify-content: space-between; align-items: center;
    }
    h2 {
      color: var(--vino);
      margin-bottom: 1rem;
    }
    label {
      display: block;
      margin-top: 1rem;
      font-weight: bold;
      color: var(--cafe);
    }
    input[type="text"], textarea {
      width: 100%;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    textarea { resize: vertical; height: 100px; }
    input[type="file"] { margin-top: 0.5rem; }
    button {
      margin-top: 1.5rem;
      background-color: var(--vino);
      color: var(--beige);
      padding: 0.7rem 1.5rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    button:hover { background-color: var(--cafe); }
    .msg { text-align: center; margin-top: 1rem; color: green; font-weight: bold; }
    .logout-link { text-decoration: none; color: var(--vino); font-weight: bold; }
    .logout-link:hover { color: var(--cafe); }
  </style>
</head>
<body>
  <div class="admin-container">
    <div class="top-bar">
      <h2>Actualizar Portada del Mes</h2>
      <a class="logout-link" href="admin_logout.php">Cerrar sesión</a>
    </div>
    <?php if (!empty($mensaje)) echo "<p class='msg'>$mensaje</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
      <label>Título:</label>
      <input type="text" name="titulo" required>

      <label>Descripción:</label>
      <textarea name="descripcion" required></textarea>

      <label>Imagen (opcional):</label>
      <input type="file" name="imagen" accept="image/*">

      <button type="submit">Guardar Portada</button>
    </form>
  </div>
</body>
</html>
