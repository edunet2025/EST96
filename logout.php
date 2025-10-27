<?php
session_start();
require_once("conexion.php");
require_once("includes/funciones_login.php");

if (isset($_SESSION['id'])) {
  cerrar_sesion_completa($conn, $_SESSION['id']);
} elseif (isset($_SESSION['usuario_id'])) {
  cerrar_sesion_completa($conn, $_SESSION['usuario_id']);
} elseif (isset($_SESSION['id_tmp'])) {
  cerrar_sesion_completa($conn, $_SESSION['id_tmp']);
} else {
  // Limpieza de emergencia
  session_unset();
  session_destroy();
}

header("Location: login.php?mensaje=✅ Sesión cerrada correctamente.");
exit;
?>
