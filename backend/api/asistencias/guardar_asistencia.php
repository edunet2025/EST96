<?php
// =======================================
// Mostrar errores (solo para depuración)
// =======================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../../../conexion.php";
date_default_timezone_set('America/Mexico_City');

// =======================================
// Validar sesión
// =======================================
$tipos_permitidos = ['docente', 'orientacion', 'prefectura'];
if (!isset($_SESSION['usuario']) || !in_array(strtolower($_SESSION['tipo']), $tipos_permitidos)) {
  header("Location: ../../../login.php");
  exit;
}

// =======================================
// Recibir variables
// =======================================
$usuario = $_SESSION['usuario'];
$fecha   = $_POST['fecha']   ?? date('Y-m-d');
$materia = $_POST['materia'] ?? '';
$hora    = $_POST['hora']    ?? '';
$grado   = $_POST['grado']   ?? '';
$grupo   = $_POST['grupo']   ?? '';
$asistencias = $_POST['asistencia'] ?? [];
$observaciones = $_POST['observacion'] ?? [];

if (!$materia || !$hora || !$grado || !$grupo) {
  die("Datos incompletos");
}

// =======================================
// Guardar registros
// =======================================
foreach ($asistencias as $matricula => $valor) {
  $asistencia = $valor ? 1 : 0;
  $obs = $observaciones[$matricula] ?? null;

  $sql = "INSERT INTO asistencias (usuario, grado, grupo, materia, fecha, hora, alumno, asistencia, observacion)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE asistencia=VALUES(asistencia), observacion=VALUES(observacion)";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    die("Error en prepare(): " . $conn->error);
  }

  $stmt->bind_param("sisssssis", $usuario, $grado, $grupo, $materia, $fecha, $hora, $matricula, $asistencia, $obs);
  $stmt->execute();
}

// =======================================
// Generar archivo CSV (compatible con Excel)
// =======================================
$uploadsDir = __DIR__ . '/../../../uploads';
if (!is_dir($uploadsDir)) {
  mkdir($uploadsDir, 0775, true);
}

$filename = "pase_lista_" . $grado . $grupo . "_" . $fecha . ".csv";
$path = $uploadsDir . "/" . $filename;

// Crear archivo CSV con codificación UTF-8
$fp = fopen($path, "w");
if (!$fp) {
  die("Error al crear archivo CSV en: $path");
}

// Agregar BOM UTF-8 para Excel (acentos correctos)
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
fputcsv($fp, ["#", "Alumno", "Asistencia", "Observación"]);

$sql = "SELECT a.alumno, u.nombre, u.apellido_paterno, u.apellido_materno, a.asistencia, a.observacion
        FROM asistencias a
        JOIN usuarios u ON a.alumno COLLATE utf8mb4_unicode_ci = u.usuario COLLATE utf8mb4_unicode_ci
        WHERE a.fecha=? AND a.grado=? AND a.grupo=? AND a.materia=? AND a.hora=? AND a.usuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sissss", $fecha, $grado, $grupo, $materia, $hora, $usuario);
$stmt->execute();
$res = $stmt->get_result();
$cont = 1;

while ($row = $res->fetch_assoc()) {
  $nombre = "{$row['apellido_paterno']} {$row['apellido_materno']} {$row['nombre']}";
  fputcsv($fp, [$cont++, $nombre, $row['asistencia'] ? 'Asistió' : 'Falta', $row['observacion']]);
}
fclose($fp);

// =======================================
// Ruta pública para descarga
// =======================================
$downloadPath = "../../../uploads/" . $filename;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pase guardado</title>
  <link rel="stylesheet" href="../../../docente/css/asistencias.css?v=1">
</head>
<body>
  <main class="main-content" style="text-align:center;">
    <section class="asistencias-card" style="max-width:600px;margin:auto;">
      <h2>✅ Pase de lista enviado correctamente</h2>
      <p><strong>Materia:</strong> <?= htmlspecialchars($materia) ?> |
         <strong>Hora:</strong> <?= htmlspecialchars($hora) ?> |
         <strong>Fecha:</strong> <?= htmlspecialchars($fecha) ?></p>
      <p><strong>Grado y grupo:</strong> <?= htmlspecialchars($grado) ?>°<?= htmlspecialchars($grupo) ?></p>

      <div style="margin-top:25px;">
        <a href="<?= $downloadPath ?>" 
           download="pase_lista_<?= $grado . $grupo . '_' . $fecha ?>.csv" 
           class="btn-enviar">⬇️ Descargar Excel (.csv)</a>
        <a href="../../../docente/menu-docente.php" 
           class="btn-toggle" 
           style="margin-left:10px;">🏠 Menú principal</a>
      </div>
    </section>
  </main>
</body>
</html>
