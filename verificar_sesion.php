<?php
session_start();
require_once("../conexion.php");
require_once("../includes/funciones_login.php");

if (!isset($_SESSION['usuario']) || !isset($_SESSION['session_token'])) {
  header("Location: ../login.php");
  exit;
}

$usuario_id = $_SESSION['usuario_id'];
$token = $_SESSION['session_token'];

$stmt = $conn->prepare("SELECT session_token FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($token_db);
$stmt->fetch();
$stmt->close();

if ($token_db !== $token) {
  cerrar_sesion_completa($conn, $usuario_id);
  header("Location: ../login.php?error=⚠️ Tu sesión expiró o fue cerrada en otro dispositivo.");
  exit;
}

$conn->query("UPDATE usuarios SET ultima_actividad = NOW() WHERE id = $usuario_id");
?>
