<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../../../conexion.php";

$tipos_permitidos = ['docente', 'orientacion', 'prefectura'];
if (!isset($_SESSION['usuario']) || !in_array(strtolower($_SESSION['tipo']), $tipos_permitidos)) {
  header("Location: ../../../login.php");
  exit;
}

$fecha   = $_GET['fecha'] ?? date('Y-m-d');
$materia = $_GET['materia'] ?? '';
$hora    = $_GET['hora'] ?? '';
$grado   = $_GET['grado'] ?? '';
$grupo   = $_GET['grupo'] ?? '';

if (!$materia || !$hora || !$grado || !$grupo) {
  die("Datos incompletos");
}

// Obtener alumnos
$sql = "SELECT usuario AS matricula, nombre, apellido_paterno, apellido_materno
        FROM usuarios
        WHERE tipo='alumno' AND grado=? AND grupo=?
        ORDER BY apellido_paterno, apellido_materno, nombre";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $grado, $grupo);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pase de Lista</title>

  <link rel="stylesheet" href="../../../docente/css/menu-docente.css?v=1">
  <link rel="stylesheet" href="../../../docente/css/asistencias.css?v=1">
  <script src="../../../docente/js/asistencias.js" defer></script>
</head>
<body>
  <main class="main-content">
    <section class="asistencias-card">
      <h2>📋 Pase de Lista - <?= htmlspecialchars($grado) ?>°<?= htmlspecialchars($grupo) ?></h2>
      <p><strong>Materia:</strong> <?= htmlspecialchars($materia) ?> | 
         <strong>Hora:</strong> <?= htmlspecialchars($hora) ?> | 
         <strong>Fecha:</strong> <?= htmlspecialchars($fecha) ?></p>

      <form action="guardar_asistencia.php" method="post">
        <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
        <input type="hidden" name="materia" value="<?= htmlspecialchars($materia) ?>">
        <input type="hidden" name="hora" value="<?= htmlspecialchars($hora) ?>">
        <input type="hidden" name="grado" value="<?= htmlspecialchars($grado) ?>">
        <input type="hidden" name="grupo" value="<?= htmlspecialchars($grupo) ?>">

        <div class="acciones-grupo">
          <button type="button" id="marcarTodos" class="btn-toggle">☑️ Marcar todos</button>
          <button type="button" id="desmarcarTodos" class="btn-toggle btn-cancel">❌ Desmarcar todos</button>
        </div>

        <table class="tabla-lista">
          <thead>
            <tr>
              <th>#</th>
              <th>Alumno</th>
              <th>Asistencia</th>
              <th>Observaciones</th>
            </tr>
          </thead>
          <tbody>
            <?php $n=1; while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $n++ ?></td>
                <td><?= htmlspecialchars($row['apellido_paterno']." ".$row['apellido_materno']." ".$row['nombre']) ?></td>
                <td style="text-align:center;">
                  <input type="checkbox" name="asistencia[<?= htmlspecialchars($row['matricula']) ?>]" value="1">
                </td>
                <td>
                  <textarea name="observacion[<?= htmlspecialchars($row['matricula']) ?>]" placeholder="Ej. llegó tarde, sin uniforme..."></textarea>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <div style="margin-top:20px; text-align:right;">
          <button class="btn-enviar" type="submit">Guardar asistencia ✅</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>
