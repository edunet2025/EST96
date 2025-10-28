<?php
require_once __DIR__ . '/../../conexion.php';
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF);

$nombre = trim($_GET['nombre'] ?? '');
$grado  = trim($_GET['grado'] ?? '');
$grupo  = trim($_GET['grupo'] ?? '');

if ($nombre === '' || $grado === '' || $grupo === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Faltan parámetros']);
  exit;
}

$nombreLike = "%$nombre%";

$sql = "SELECT usuario AS matricula,
               CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo,
               grado, grupo
        FROM usuarios
        WHERE tipo = 'alumno'
          AND grado = ?
          AND grupo = ?
          AND (
            nombre LIKE ? OR
            apellido_paterno LIKE ? OR
            apellido_materno LIKE ?
          )
        LIMIT 10";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  echo json_encode(['error' => $conn->error]);
  exit;
}

$stmt->bind_param("issss", $grado, $grupo, $nombreLike, $nombreLike, $nombreLike);
$stmt->execute();
$res = $stmt->get_result();
$alumnos = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

if (count($alumnos) === 0) {
  http_response_code(404);
  echo json_encode(['error' => 'Sin coincidencias']);
  exit;
}

// Si solo hay un alumno, devolvemos directamente su matrícula
if (count($alumnos) === 1) {
  echo json_encode(['matricula' => $alumnos[0]['matricula']], JSON_UNESCAPED_UNICODE);
  exit;
}

// Si hay varios, devolvemos lista para elegir
echo json_encode(['candidatos' => $alumnos], JSON_UNESCAPED_UNICODE);
