<?php
session_start();
date_default_timezone_set('America/Mexico_City');

if (!isset($_SESSION['nombre']) || $_SESSION['tipo'] !== 'docente') {
  header('Location: ../login.php');
  exit;
}

$nombre_docente = $_SESSION['nombre'];
$matricula_docente = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reporte de Conducta — E.S.T. 96 Miguel Alemán Valdés</title>
<link rel="stylesheet" href="css/reporte.css?v=2">
</head>
<body>
  <!-- ======= ENCABEZADO INSTITUCIONAL ======= -->
<header class="encabezado">
  <div class="encabezado-contenido">
    <img src="/img/logo.png" alt="Escudo Escuela" class="logo">
    <div class="titulo">
      <h2>Escuela Secundaria Técnica No. 96</h2>
      <h3>“Miguel Alemán Valdés”</h3>
    </div>
  </div>
</header>

<div class="wrapper">
  <div class="card">
    <h1>🧾 Reporte de Conducta</h1>
    <p class="muted">Puedes agregar más de un alumno al mismo reporte. El prefecto se asigna automáticamente.</p>

    <form id="frmReporte" class="grid">
      <div class="full">
        <span class="badge" id="docInfo">Docente: —</span>
      </div>

      <div id="alumnosContainer">
        <div class="alumno-block">
          <div class="full">
            <label>Matrícula del alumno</label>
            <input class="matricula" required placeholder="Ej. 002 o ROCJ940719A">
            <small class="muted lookupMsg"></small>
          </div>

          <div class="full row-scan">
            <input class="scanFile" type="file" accept="image/*" capture="environment">
            <button type="button" class="btn btn-ghost btnScan">📷 Escanear formato</button>
            <small class="muted scanMsg"></small>
          </div>

          <div>
            <label>Nombre del alumno</label>
            <input class="nombre" placeholder="Se llena automáticamente">
          </div>

          <div>
            <label>Prefecto asignado (auto)</label>
            <input class="prefecto" readonly placeholder="Se asignará según grado y grupo">
          </div>

          <div>
            <label>Grado</label>
            <select class="grado" required>
              <option value="">Grado</option><option>1</option><option>2</option><option>3</option>
            </select>
          </div>

          <div>
            <label>Grupo</label>
            <select class="grupo" required>
              <option value="">Grupo</option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option>
            </select>
          </div>
        </div>
      </div>

      <button type="button" id="btnAddAlumno" class="btn verde full">+ Agregar otro alumno</button>

      <div>
        <label>Clase</label>
        <select id="clase" required>
          <option value="">Selecciona</option>
          <option>Tecnología</option><option>Español</option><option>Matemáticas</option><option>Ciencias</option>
          <option>Inglés</option><option>Historia</option><option>Formación Cívica</option>
          <option>Artes</option><option>Educación Física</option><option>Otra</option>
        </select>
      </div>

      <div>
        <label>Hora</label>
        <select id="hora" required>
          <option value="">Selecciona</option><option>1ra</option><option>2da</option><option>3ra</option>
          <option>4ta</option><option>5ta</option><option>6ta</option>
        </select>
      </div>

      <div class="full">
        <label>Descripción de la conducta</label>
        <textarea id="contenido" required placeholder="Describe el incidente."></textarea>
      </div>

      <div class="row full">
        <button type="reset" class="btn btn-ghost">Limpiar</button>
        <button type="submit" class="btn btn-primary">Enviar reporte</button>
      </div>

      <div class="full">
        <p id="statusMsg" class="muted"></p>
        <p id="reciboLink" style="display:none">
          ✅ Comprobante listo:
          <a id="aRecibo" target="_blank" rel="noopener">Abrir comprobante</a>
        </p>
      </div>
    </form>
  </div>
</div>

<script>
const PHP_NOMBRE = <?= json_encode($nombre_docente) ?>;
const PHP_MATRICULA = <?= json_encode($matricula_docente) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script src="js/reporte.js?v=2"></script>
</body>
</html>
