<?php
require_once __DIR__ . '/../../conexion.php';
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF);

$nombre = trim($_GET['nombre'] ?? '');
if ($nombre === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Falta el parámetro nombre']);
  exit;
}

$nombreLike = "%$nombre%";

// Buscamos alumnos por coincidencia de nombre o apellidos
$sql = "SELECT usuario AS matricula,
               CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo,
               grado, grupo
        FROM usuarios
        WHERE tipo = 'alumno'
          AND (
            nombre LIKE ? OR
            apellido_paterno LIKE ? OR
            apellido_materno LIKE ?
          )
        ORDER BY grado, grupo, nombre
        LIMIT 15";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombreLike, $nombreLike, $nombreLike);
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

// devolvemos lista de sugerencias
echo json_encode(['candidatos' => $alumnos], JSON_UNESCAPED_UNICODE);
