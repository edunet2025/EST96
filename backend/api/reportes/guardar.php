<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=utf-8");
require_once("../../conexion.php");
require_once("../../utils/correo.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['docente']) || empty($data['alumnos'])) {
  echo json_encode(["ok" => false, "error" => "Datos incompletos"]);
  exit;
}

$docente = $data['docente'];
$prefecto = $data['prefecto'] ?? 'Automático';
$clase = $data['clase'] ?? '';
$hora = $data['hora'] ?? '';
$contenido = $data['contenido'] ?? '';
$alumnos = $data['alumnos'] ?? [];

// 1️⃣ Crear nuevo folio
$conn->begin_transaction();
try {
  $folio = 1;
  $res = $conn->query("SELECT MAX(folio) AS max_folio FROM reportes");
  if ($r = $res->fetch_assoc()) $folio = intval($r['max_folio']) + 1;

  // 2️⃣ Insertar cada alumno (todos comparten el mismo folio)
  $stmt = $conn->prepare("INSERT INTO reportes 
    (folio, matricula_docente, nombre_docente, matricula_alumno, nombre_alumno,
     grado, grupo, clase, hora, contenido, registrado_por, tipo_origen)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'docente')");

  foreach ($alumnos as $a) {
    $stmt->bind_param("isssssissss",
      $folio,
      $docente['usuario'],
      $docente['nombre'],
      $a['matricula'],
      $a['nombre'],
      $a['grado'],
      $a['grupo'],
      $clase,
      $hora,
      $contenido,
      $prefecto
    );
    $stmt->execute();
  }

  $conn->commit();

  // 3️⃣ Enviar correo
  $correoDocente = obtenerCorreoDocente($conn, $docente['usuario']);
  $urlRecibo = "https://miguelaleman.edunet.com.mx/backend/api/reportes/recibo.php?folio=$folio";
  enviarCorreoReporte($correoDocente, $docente['nombre'], $folio, $urlRecibo);

  echo json_encode(["ok" => true, "folio" => $folio, "recibo_url" => $urlRecibo]);
} catch (Exception $e) {
  $conn->rollback();
  echo json_encode(["ok" => false, "error" => "Error al guardar reporte: " . $e->getMessage()]);
}

function obtenerCorreoDocente($conn, $usuario) {
  $stmt = $conn->prepare("SELECT correo_electronico FROM usuarios WHERE usuario = ? LIMIT 1");
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  $res = $stmt->get_result();
  $fila = $res->fetch_assoc();
  return $fila['correo_electronico'] ?? "no-reply@miguelaleman.edunet.com.mx";
}
?>
