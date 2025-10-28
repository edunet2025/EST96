<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=utf-8");

require_once("../../conexion.php");
require_once("../../utils/correo.php");

// 1. Leer JSON del front
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
  echo json_encode(["ok"=>false,"error"=>"JSON inválido"]);
  exit;
}

// validar campos mínimos
if (empty($data['docente']['usuario']) || empty($data['docente']['nombre'])) {
  echo json_encode(["ok"=>false,"error"=>"Falta información del docente"]);
  exit;
}
if (empty($data['alumnos']) || !is_array($data['alumnos'])) {
  echo json_encode(["ok"=>false,"error"=>"No hay alumnos"]);
  exit;
}
if (empty($data['clase']) || empty($data['hora']) || empty($data['contenido'])) {
  echo json_encode(["ok"=>false,"error"=>"Faltan datos del incidente"]);
  exit;
}

$docente_usuario = $data['docente']['usuario']; // ej. docente01
$docente_nombre  = $data['docente']['nombre'];  // ej. Laura Morales
$clase           = $data['clase'];
$hora            = $data['hora'];
$contenido       = $data['contenido'];
$alumnos         = $data['alumnos'];

try {
  $conn->begin_transaction();

  // 2. Generar folio = max(folio)+1
  $resFolio = $conn->query("SELECT IFNULL(MAX(folio),0)+1 AS nuevo_folio FROM reportes");
  $rowFolio = $resFolio->fetch_assoc();
  $folio    = intval($rowFolio['nuevo_folio']);

  // 3. Preparar INSERT
  // Coincide EXACTO con tu tabla (incluye prefecto_asignado, registrado_por, tipo_origen, estado)
  $sql = "INSERT INTO reportes (
            folio,
            matricula_docente,
            nombre_docente,
            matricula_alumno,
            nombre_alumno,
            grado,
            grupo,
            clase,
            hora,
            contenido,
            prefecto_asignado,
            registrado_por,
            tipo_origen,
            estado,
            creado_en
          ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'docente', 'enviado', NOW()
          )";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    throw new Exception("Error prepare: " . $conn->error);
  }

  foreach ($alumnos as $al) {
    $matricula_alumno   = $al['matricula'] ?? '';
    $nombre_alumno      = $al['nombre'] ?? '';
    $grado              = $al['grado'] ?? '';
    $grupo              = $al['grupo'] ?? '';
    $prefecto_asignado  = $al['prefecto_asignado'] ?? '';

    if (!$matricula_alumno || !$nombre_alumno || !$grado || !$grupo) {
      throw new Exception("Alumno incompleto en la solicitud");
    }

    // bind_param tipos:
    // folio                i (int)
    // matricula_docente    s
    // nombre_docente       s
    // matricula_alumno     s
    // nombre_alumno        s
    // grado                i (tinyint)
    // grupo                s (char)
    // clase                s
    // hora                 s
    // contenido            s
    // prefecto_asignado    s
    // registrado_por       s
    $stmt->bind_param(
      "isssssisssss",
      $folio,
      $docente_usuario,
      $docente_nombre,
      $matricula_alumno,
      $nombre_alumno,
      $grado,
      $grupo,
      $clase,
      $hora,
      $contenido,
      $prefecto_asignado,
      $prefecto_asignado // lo guardamos también como registrado_por
    );

    if (!$stmt->execute()) {
      throw new Exception("Error execute: " . $stmt->error);
    }
  }

  $conn->commit();

  // 4. Armar URL de comprobante
  $recibo_url = "https://miguelaleman.edunet.com.mx/backend/api/reportes/recibo.php?folio=" . $folio;

  // 5. Buscar correo del docente en tabla usuarios
  $correo_docente = "no-reply@miguelaleman.edunet.com.mx";
  $getMail = $conn->prepare("SELECT correo_electronico FROM usuarios WHERE usuario = ? LIMIT 1");
  $getMail->bind_param("s", $docente_usuario);
  $getMail->execute();
  $resMail = $getMail->get_result();
  if ($resMail && $resMail->num_rows === 1) {
    $filaMail = $resMail->fetch_assoc();
    if (!empty($filaMail['correo_electronico'])) {
      $correo_docente = $filaMail['correo_electronico'];
    }
  }

  // 6. Enviar correo
  enviarCorreoReporte(
    $correo_docente,
    $docente_nombre,
    $folio,
    $recibo_url
  );

  echo json_encode([
    "ok" => true,
    "folio" => $folio,
    "recibo_url" => $recibo_url
  ]);

} catch (Exception $e) {
  $conn->rollback();
  echo json_encode([
    "ok" => false,
    "error" => $e->getMessage()
  ]);
}
?>

