<?php
include("conexion.php");

$sql = "SELECT id, contrasena FROM usuarios";
$resultado = $conn->query($sql);

while ($fila = $resultado->fetch_assoc()) {
    $id = $fila['id'];
    $contrasena = $fila['contrasena'];

    if (strpos($contrasena, '$2y$') === 0) {
        echo "🔒 La contraseña del usuario con ID $id ya estaba encriptada<br>";
        continue;
    }
    $contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
    $update->bind_param("si", $contrasenaEncriptada, $id);
    $update->execute();

    echo "✅ Contraseña del usuario con ID $id encriptada correctamente<br>";
}

$conn->close();
?>
