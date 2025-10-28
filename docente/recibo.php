<?php
require_once("../conexion.php");
date_default_timezone_set('America/Mexico_City');

$folio = intval($_GET['folio'] ?? 0);
if(!$folio){ echo "Folio inválido"; exit; }

$q = $conn->prepare("SELECT * FROM reportes WHERE folio = ?");
$q->bind_param("i",$folio);
$q->execute();
$r = $q->get_result();
if(!$r->num_rows){ echo "Reporte no encontrado."; exit; }
$rows = $r->fetch_all(MYSQLI_ASSOC);
$doc = $rows[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Comprobante Folio #<?= $folio ?></title>
<link rel="stylesheet" href="../css/reporte.css">
</head>
<body>
<div class="contenedor">
<div class="card">
  <header class="encabezado">
    <img src="../img/logo.png" class="logo">
    <div>
      <h1>E.S.T. 96 “Miguel Alemán Valdés”</h1>
      <p>Comprobante de Reporte Disciplinario</p>
    </div>
  </header>
  <p><b>Folio:</b> <?= $folio ?><br>
     <b>Docente:</b> <?= htmlspecialchars($doc['nombre_docente']) ?><br>
     <b>Clase:</b> <?= htmlspecialchars($doc['clase']) ?><br>
     <b>Hora:</b> <?= htmlspecialchars($doc['hora']) ?><br>
     <b>Registrado por:</b> <?= htmlspecialchars($doc['registrado_por']) ?><br>
     <b>Fecha:</b> <?= date("d/m/Y H:i", strtotime($doc['creado_en'])) ?></p>
  <hr>
  <h3>Alumnos involucrados</h3>
  <ul>
  <?php foreach($rows as $x): ?>
    <li><?= htmlspecialchars($x['nombre_alumno']) ?> — <?= $x['grado'] ?>°<?= $x['grupo'] ?></li>
  <?php endforeach; ?>
  </ul>
  <h3>Descripción</h3>
  <p><?= nl2br(htmlspecialchars($doc['contenido'])) ?></p>
</div>
</div>
</body>
</html>
