<?php
session_start();
require_once __DIR__ . "/../../../conexion.php";

if (!isset($_SESSION['usuario']) || strtolower($_SESSION['tipo']) !== 'docente') {
  die("Acceso denegado");
}

$usuario = $_SESSION['usuario'];
$fecha   = $_GET['fecha'] ?? '';
$materia = $_GET['materia'] ?? '';
$hora    = $_GET['hora'] ?? '';
$grado   = $_GET['grado'] ?? '';
$grupo   = $_GET['grupo'] ?? '';

if (!$fecha || !$materia || !$hora || !$grado || !$grupo) {
  die("Faltan parámetros");
}

$sql = "SELECT a.*, u.nombre, u.apellido_paterno, u.apellido_materno
        FROM asistencias a
        JOIN usuarios u ON a.alumno = u.usuario
        WHERE a.usuario = ? AND a.fecha = ? AND a.materia = ? AND a.hora = ? AND a.grado = ? AND a.grupo = ?
        ORDER BY u.apellido_paterno, u.apellido_materno, u.nombre";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssis", $usuario, $fecha, $materia, $hora, $grado, $grupo);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprobante de Pase de Lista</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; background: #f7f7f7; color: #333; }
    .container { background: #fff; padding: 20px 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #003366; }
    p.info { text-align: center; font-size: 15px; margin-top: -5px; color: #555; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
    th { background: #0057b7; color: #fff; }
    tr:nth-child(even) { background: #f2f2f2; }
    .firmas { margin-top: 40px; display: flex; justify-content: space-between; }
    .firmas div { width: 45%; text-align: center; }
    .btn-print { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #0057b7; color: white; border-radius: 6px; text-decoration: none; }
    .btn-print:hover { background: #004095; }
  </style>
</head>
<body>
  <div class="container">
    <h2>📋 Comprobante de Pase de Lista</h2>
    <p class="info">Grado: <?= $grado ?>°<?= $grupo ?> | Materia: <?= htmlspecialchars($materia) ?> | Fecha: <?= htmlspecialchars($fecha) ?> | Hora: <?= htmlspecialchars($hora) ?></p>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Alumno</th>
          <th>Asistencia</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $n = 1;
        while ($row = $result->fetch_assoc()):
          $nombreCompleto = $row['apellido_paterno'] . ' ' . $row['apellido_materno'] . ' ' . $row['nombre'];
          $asistenciaTxt = $row['asistencia'] == 1 ? '✅ Asistió' : '❌ Falta';
        ?>
        <tr>
          <td><?= $n++ ?></td>
          <td><?= htmlspecialchars($nombreCompleto) ?></td>
          <td><?= $asistenciaTxt ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="firmas">
      <div>
        ____________________________<br>
        <strong>Firma del Docente</strong>
      </div>
      <div>
        ____________________________<br>
        <strong>Firma del Prefecto / Control Escolar</strong>
      </div>
    </div>

    <div style="text-align:center;">
      <a href="#" class="btn-print" onclick="window.print()">🖨️ Imprimir / Guardar PDF</a>
      <a href="../../../docente/asistencias.php" class="btn-print" style="background:#888;">⬅️ Regresar</a>
    </div>
  </div>
</body>
</html>
