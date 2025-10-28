<?php
header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../conexion.php");

$matricula = trim($_GET['matricula'] ?? '');

if ($matricula === '') {
  echo json_encode(["error" => "Falta matrícula"]);
  exit;
}

$stmt = $conn->prepare("
  SELECT 
    usuario           AS matricula,
    nombre,
    apellido_paterno,
    apellido_materno,
    grado,
    grupo
  FROM usuarios
  WHERE usuario = ? AND tipo = 'alumno'
  LIMIT 1
");
$stmt->bind_param("s", $matricula);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  echo json_encode(["error" => "No encontrado"]);
  exit;
}

$al = $res->fetch_assoc();
$al['nombre_completo'] = trim(
  ($al['nombre'] ?? '') . ' ' .
  ($al['apellido_paterno'] ?? '') . ' ' .
  ($al['apellido_materno'] ?? '')
);

echo json_encode($al);
?>
