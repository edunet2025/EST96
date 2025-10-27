<?php
session_start();
require_once("conexion.php");
require_once("includes/funciones_login.php");

if (isset($_SESSION['usuario_id'])) {
  cerrar_sesion_completa($conn, $_SESSION['usuario_id']);
}

header("Location: login.php");
exit;
