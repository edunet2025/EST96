<?php
function redirigir_segun_tipo($tipo) {
  $tipo = strtolower(trim($tipo));
  switch ($tipo) {
    case 'alumno':
      header("Location: alumno/menu-alumno.php");
      break;
    case 'maestro':
    case 'docente':
      header("Location: docente/menu-docente.php");
      break;
    case 'admin':
    case 'administrativo':
    case 'supervision':
      header("Location: ad/menu-admin.php");
      break;
    case 'secretario':
      header("Location: secretario/menu-secretario.php");
      break;
    case 'orientacion':
      header("Location: orientacion/menu-orientacion.php");
      break;
    case 'prefecto':
    case 'prefectura':
      header("Location: prefectura/menu-prefectura.php");
      break;
    default:
      header("Location: login.php?error=tipo_desconocido");
      break;
  }
  exit();
}

function cerrar_sesion_completa($conn, $usuario_id) {
  $stmt = $conn->prepare("UPDATE usuarios SET session_token = NULL WHERE id = ?");
  $stmt->bind_param("i", $usuario_id);
  $stmt->execute();
  $stmt->close();

  $_SESSION = [];
  session_destroy();
}

function evitar_cache() {
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
}
?>
