<?php
require_once("../../conexion.php");
date_default_timezone_set('America/Mexico_City');

$folio = isset($_GET['folio']) ? intval($_GET['folio']) : 0;

if ($folio <= 0) {
  echo "<h3 style='color:red;text-align:center;'>⚠️ Folio inválido.</h3>";
  exit;
}

$sql = "SELECT 
  folio,
  matricula_docente,
  nombre_docente,
  matricula_alumno,
  nombre_alumno,
  grado,
  grupo,
  clase,
  hora,
  contenido,
  prefecto_asignado,
  registrado_por,
  tipo_origen,
  estado,
  creado_en
FROM reportes
WHERE folio = ?
ORDER BY id ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $folio);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  echo "<h3 style='color:red;text-align:center;'>❌ No se encontró el reporte.</h3>";
  exit;
}

$reportes = [];
while ($r = $res->fetch_assoc()) {
  $reportes[] = $r;
}
$info = $reportes[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Comprobante de Reporte — Folio <?= htmlspecialchars($folio) ?></title>
<style>
body{
  font-family:system-ui,Segoe UI,Arial,sans-serif;
  background:#f4f6fa;
  margin:0;
  padding:0;
  color:#222;
}
.container{
  max-width:850px;
  margin:2rem auto;
  background:#fff;
  border-radius:10px;
  box-shadow:0 4px 20px rgba(0,0,0,0.1);
  padding:2rem;
}
header{
  text-align:center;
  border-bottom:2px solid #c81e1e;
  padding-bottom:10px;
  margin-bottom:20px;
}
header h1{
  margin:0;
  font-size:1.6rem;
  color:#c81e1e;
}
header p{
  color:#444;
  margin:5px 0;
}
section{
  margin-bottom:1.5rem;
}
h2{
  font-size:1.1rem;
  color:#0f2a6d;
  border-bottom:1px solid #ddd;
  padding-bottom:4px;
}
table{
  width:100%;
  border-collapse:collapse;
  margin-top:.5rem;
}
td,th{
  padding:6px 8px;
  border:1px solid #ddd;
  font-size:.95rem;
}
th{
  background:#f9fafc;
  text-align:left;
}
.footer{
  text-align:center;
  font-size:.85rem;
  color:#666;
  margin-top:2rem;
}
.badge{
  background:#c81e1e;
  color:#fff;
  padding:4px 10px;
  border-radius:6px;
  font-weight:bold;
}
@media print{
  body{background:white;}
  .container{box-shadow:none;margin:0;padding:0;}
  .footer{display:none;}
}
</style>
</head>
<body>
<div class="container">
  <header>
    <img src="img/logo.png" alt="Logo Escuela" width="80">
    <h1>Escuela Secundaria Técnica No. 96 “Miguel Alemán Valdés”</h1>
    <p><strong>Comprobante de Reporte Disciplinario</strong></p>
    <p>Folio: <span class="badge"><?= htmlspecialchars($folio) ?></span></p>
  </header>

  <section>
    <h2>📋 Datos del Docente</h2>
    <table>
      <tr><th>Matrícula</th><td><?= htmlspecialchars($info['matricula_docente']) ?></td></tr>
      <tr><th>Nombre</th><td><?= htmlspecialchars($info['nombre_docente']) ?></td></tr>
      <tr><th>Clase</th><td><?= htmlspecialchars($info['clase']) ?></td></tr>
      <tr><th>Hora</th><td><?= htmlspecialchars($info['hora']) ?></td></tr>
      <tr><th>Fecha de creación</th><td><?= date("d/m/Y H:i", strtotime($info['creado_en'])) ?> hrs</td></tr>
    </table>
  </section>

  <section>
    <h2>👥 Alumnos incluidos en el reporte</h2>
    <table>
      <tr>
        <th>Matrícula</th>
        <th>Nombre</th>
        <th>Grado</th>
        <th>Grupo</th>
        <th>Prefecto Asignado</th>
      </tr>
      <?php foreach ($reportes as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['matricula_alumno']) ?></td>
        <td><?= htmlspecialchars($r['nombre_alumno']) ?></td>
        <td><?= htmlspecialchars($r['grado']) ?></td>
        <td><?= htmlspecialchars($r['grupo']) ?></td>
        <td><?= htmlspecialchars($r['prefecto_asignado']) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </section>

  <section>
    <h2>📝 Descripción de la conducta</h2>
    <p><?= nl2br(htmlspecialchars($info['contenido'])) ?></p>
  </section>

  <div class="footer">
    <p>Generado automáticamente el <?= date("d/m/Y H:i") ?> desde el sistema de reportes.</p>
    <p>© 2025 Escuela Secundaria Técnica No. 96 “Miguel Alemán Valdés”</p>
  </div>
</div>
</body>
</html>
