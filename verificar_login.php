<?php
session_start();
require_once("conexion.php");
require_once("includes/funciones_login.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $usuario = trim($_POST['usuario'] ?? '');
  $contrasena = trim($_POST['contrasena'] ?? '');

  if (!$usuario || !$contrasena) {
    header("Location: login.php?error=⚠️ Llena ambos campos."); exit;
  }

  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows === 1) {
    $fila = $resultado->fetch_assoc();

    if (password_verify($contrasena, $fila['contrasena'])) {
      $continuar = true;

      if (!empty($fila['session_token'])) {
        $ultima = strtotime($fila['ultima_actividad']);
        $ahora = time();
        $diferencia_min = ($ahora - $ultima) / 60;
        if ($diferencia_min < 20) {
          header("Location: login.php?error=⚠️ Ya tienes una sesión activa en otro dispositivo.");
          exit;
        } else {
          $conn->query("UPDATE usuarios SET session_token = NULL WHERE usuario = '$usuario'");
        }
      }

      if ((int)$fila['must_change_pass'] === 1) {
        $_SESSION['usuario_tmp'] = $fila['usuario'];
        $_SESSION['id_tmp'] = $fila['id'];
        $_SESSION['tipo_tmp'] = $fila['tipo'];
        header("Location: cambiar_contrasena.php");
        exit;
      }

      $token = bin2hex(random_bytes(32));

      $_SESSION['usuario'] = $fila['usuario'];
      $_SESSION['tipo'] = $fila['tipo'];
      $_SESSION['id'] = $fila['id'];
      $_SESSION['nombre'] = $fila['nombre'];
      $_SESSION['session_token'] = $token;
      $_SESSION['usuario_id'] = $fila['id'];

      $update = $conn->prepare("UPDATE usuarios SET session_token = ?, ultima_actividad = NOW() WHERE id = ?");
      $update->bind_param("si", $token, $fila['id']);
      $update->execute();

      redirigir_segun_tipo($fila['tipo']);
    } else {
      header("Location: login.php?error=❌ Contraseña incorrecta."); exit;
    }
  } else {
    header("Location: login.php?error=❌ Usuario no encontrado."); exit;
  }

  $stmt->close();
} else {
  header("Location: login.php");
  exit;
}
?>
