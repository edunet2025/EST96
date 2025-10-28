<?php
require_once("../../conexion.php");
$folio = intval($_GET['folio'] ?? 0);

if ($folio <= 0) { echo "Folio no válido"; exit; }

$res = $conn->query("SELECT * FROM reportes WHERE folio = $folio");
if ($res->num_rows === 0) { echo "Folio no encontrado"; exit; }

$rows = $res->fetch_all(MYSQLI_ASSOC);
$docente = $rows[0]['nombre_docente'];
$clase = $rows[0]['clase'];
$hora = $rows[0]['hora'];
$contenido = $rows[0]['contenido'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Comprobante Folio <?= htmlspecialchars($folio) ?></title>
<style>
body{font-family:Arial;background:#f6f7fb;padding:20px;color:#222}
h1{color:#8B0000}
table{border-collapse:collapse;width:100%;margin-top:10px}
td,th{border:1px solid #ccc;padding:6px;text-align:left}
</style>
</head>
<body>
<h1>Comprobante de Reporte — Folio <?= $folio ?></h1>
<p><strong>Docente:</strong> <?= htmlspecialchars($docente) ?></p>
<p><strong>Clase:</strong> <?= htmlspecialchars($clase) ?> | <strong>Hora:</strong> <?= htmlspecialchars($hora) ?></p>
<p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($contenido)) ?></p>

<table>
<tr><th>Alumno</th><th>Grado</th><th>Grupo</th></tr>
<?php foreach ($rows as $r): ?>
<tr>
  <td><?= htmlspecialchars($r['nombre_alumno']) ?></td>
  <td><?= htmlspecialchars($r['grado']) ?></td>
  <td><?= htmlspecialchars($r['grupo']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<p style="margin-top:20px;color:#777">E.S.T. No. 96 “Miguel Alemán Valdés” — Sistema de Reportes</p>
</body>
</html>
