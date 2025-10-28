<?php
require_once __DIR__ . '/../../conexion.php';
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF);

$matricula = trim($_GET['matricula'] ?? '');
if ($matricula === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Matrícula requerida']);
  exit;
}

$sql = "SELECT usuario AS matricula,
               CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo,
               grado, grupo
        FROM usuarios
        WHERE tipo = 'alumno' AND usuario = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $matricula);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $al = $res->fetch_assoc()) {
  echo json_encode($al, JSON_UNESCAPED_UNICODE);
} else {
  http_response_code(404);
  echo json_encode(['error' => 'Alumno no encontrado']);
}
$stmt->close();
$conn->close();
