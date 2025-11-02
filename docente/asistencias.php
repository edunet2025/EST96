<?php
session_start();
require_once __DIR__ . "/../conexion.php";

// ===================================
// VERIFICAR SESIÓN Y PERFILES PERMITIDOS
// ===================================
$tipos_permitidos = ['docente', 'orientacion', 'prefectura'];
if (!isset($_SESSION['usuario']) || !in_array(strtolower($_SESSION['tipo']), $tipos_permitidos)) {
  header("Location: ../login.php");
  exit;
}

$mensaje = "";

// ===================================
// PROCESAR FORMULARIO DE PASE DE LISTA
// ===================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fecha   = $_POST['fecha'] ?? date('Y-m-d');
  $materia = $_POST['materia'] ?? '';
  $hora    = $_POST['hora'] ?? '';
  $grado   = $_POST['grado'] ?? '';
  $grupo   = $_POST['grupo'] ?? '';

  if ($fecha && $materia && $hora && $grado && $grupo) {
    header("Location: ../backend/api/asistencias/pase_lista.php" . urlencode($fecha) .
                                   "&materia=" . urlencode($materia) .
                                   "&hora=" . urlencode($hora) .
                                   "&grado=" . urlencode($grado) .
                                   "&grupo=" . urlencode($grupo));
    exit;
  } else {
    $mensaje = "⚠️ Por favor completa todos los campos.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pase de Lista | Panel Autorizado</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/menu.css">
  <link rel="stylesheet" href="../css/asistencias.css">
  <script src="../js/asistencias.js" defer></script>
</head>
<body>

  <?php include "../header.php"; ?>

  <main class="main-content">
    <section class="asistencias-card">
      <h2>📋 Registro de Asistencia</h2>

      <?php if ($mensaje): ?>
        <div class="alerta advertencia"><?= htmlspecialchars($mensaje) ?></div>
      <?php endif; ?>

      <form method="post" class="asistencias-form">
        <div class="campo">
          <label>📅 Fecha:</label>
          <input type="date" name="fecha" required value="<?= date('Y-m-d') ?>">
        </div>

        <div class="campo">
          <label>📘 Materia:</label>
          <select name="materia" required>
            <option value="">-- Selecciona materia --</option>
            <option>Español</option>
            <option>Matemáticas</option>
            <option>Ciencias</option>
            <option>Tecnología</option>
            <option>Inglés</option>
            <option>Historia</option>
            <option>Geografía</option>
            <option>Formación Cívica</option>
            <option>Artes</option>
            <option>Educación Física</option>
            <option>Tutoría</option>
            <option>Otra</option>
          </select>
        </div>

        <div class="campo">
          <label>⏰ Hora:</label>
          <select name="hora" required>
            <option value="">-- Selecciona hora --</option>
            <option>1ra</option>
            <option>2da</option>
            <option>3ra</option>
            <option>4ta</option>
            <option>5ta</option>
            <option>6ta</option>
            <option>7ma</option>
            <option>8va</option>
          </select>
        </div>

        <div class="campo">
          <label>📚 Grado:</label>
          <select name="grado" required>
            <option value="">-- Selecciona grado --</option>
            <option value="1">1°</option>
            <option value="2">2°</option>
            <option value="3">3°</option>
          </select>
        </div>

        <div class="campo">
          <label>👥 Grupo:</label>
          <select name="grupo" required>
            <option value="">-- Selecciona grupo --</option>
            <option>A</option>
            <option>B</option>
            <option>C</option>
            <option>D</option>
            <option>E</option>
          </select>
        </div>

        <div class="campo">
          <label>👤 Usuario activo:</label>
          <input type="text" value="<?= htmlspecialchars($_SESSION['usuario']) ?>" readonly>
        </div>

        <button type="submit" class="btn-enviar">Continuar ➡️</button>
      </form>
    </section>
  </main>

  <?php include "../footer.php"; ?>

</body>
</html>
