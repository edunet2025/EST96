<?php
session_start();
require_once __DIR__ . "/../../../conexion.php";

if (!isset($_SESSION['usuario']) || strtolower($_SESSION['tipo']) !== 'docente') {
  die("Acceso denegado");
}

$usuario = $_SESSION['usuario'];
$fecha   = $_POST['fecha'] ?? date('Y-m-d');
$materia = $_POST['materia'] ?? '';
$hora    = $_POST['hora'] ?? '';
$grado   = $_POST['grado'] ?? '';
$grupo   = $_POST['grupo'] ?? '';
$asistencias = $_POST['asistencia'] ?? [];

if (!$materia || !$hora || !$grado || !$grupo) {
  die("Datos incompletos");
}

$observaciones = $_POST['observacion'] ?? [];

foreach ($asistencias as $matricula => $valor) {
  $asistencia = $valor ? 1 : 0;
  $obs = $observaciones[$matricula] ?? null;

  $sql = "INSERT INTO asistencias (usuario, grado, grupo, materia, fecha, hora, alumno, asistencia, observacion)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE asistencia=VALUES(asistencia), observacion=VALUES(observacion)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sisssssis", $usuario, $grado, $grupo, $materia, $fecha, $hora, $matricula, $asistencia, $obs);
  $stmt->execute();
}


echo "<script>
alert('✅ Asistencia guardada correctamente');
window.location.href='../../../docente/asistencias.php';
</script>";
