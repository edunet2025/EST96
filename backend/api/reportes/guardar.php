<?php
require_once __DIR__ . '/../../../conexion.php';
require_once __DIR__ . '/../../utils/correo.php';
date_default_timezone_set('America/Mexico_City');
header('Content-Type: application/json; charset=utf-8');

try {
  $data = json_decode(file_get_contents("php://input"), true);
  if (!$data) throw new Exception("Datos inválidos");

  $docente  = $data['docente'] ?? [];
  $prefecto = $data['prefecto'] ?? 'N/A';
  $clase    = trim($data['clase'] ?? '');
  $hora     = trim($data['hora'] ?? '');
  $contenido= trim($data['contenido'] ?? '');
  $alumnos  = $data['alumnos'] ?? [];
  if (!$docente || empty($alumnos)) throw new Exception("Datos incompletos");

  $conn->begin_transaction();

  // Folio secuencial
  $res = $conn->query("SELECT IFNULL(MAX(folio),0)+1 AS nuevo FROM reportes");
  $folio = $res->fetch_assoc()['nuevo'];

  $sql = "INSERT INTO reportes 
          (folio, matricula_docente, nombre_docente, matricula_alumno, nombre_alumno,
           grado, grupo, clase, hora, contenido, registrado_por, tipo_origen, estado, creado_en)
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?, 'enviado', NOW())";
  $stmt = $conn->prepare($sql);

  foreach ($alumnos as $al) {
    $stmt->bind_param(
      "issssisssss",
      $folio,
      $docente['usuario'],
      $docente['nombre'],
      $al['matricula'],
      $al['nombre'],
      $al['grado'],
      $al['grupo'],
      $clase,
      $hora,
      $contenido,
      $prefecto,
      'docente'
    );
    $stmt->execute();
  }

  $conn->commit();

  $recibo_url = "https://miguelaleman.edunet.com.mx/docente/recibo.php?folio=$folio";

  $asunto = "📋 Comprobante de reporte disciplinario — Folio #{$folio}";
  $html = "
  <h2>Escuela Secundaria Técnica N.º 96 “Miguel Alemán Valdés”</h2>
  <p>Se ha registrado un nuevo reporte disciplinario.</p>
  <p><b>Folio:</b> $folio<br>
  <b>Docente:</b> {$docente['nombre']}<br>
  <b>Clase:</b> $clase<br>
  <b>Hora:</b> $hora<br>
  <b>Registrado por:</b> $prefecto</p>
  <p><a href='$recibo_url' target='_blank'>Ver comprobante</a></p>
  <hr><small>Mensaje automático del sistema E.S.T. 96</small>";

  mail_simple("{$docente['usuario']}@edunet.com.mx", $asunto, $html);

  echo json_encode(["ok"=>true,"folio"=>$folio,"recibo_url"=>$recibo_url]);
} catch (Exception $e) {
  if (isset($conn)) $conn->rollback();
  echo json_encode(["ok"=>false,"error"=>$e->getMessage()]);
}
?>
