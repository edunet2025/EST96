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
  <title>Reporte de Conducta — Docente</title>
  <link rel="stylesheet" href="../css/reporte.css?v=3">
</head>
<body>
  <main class="wrapper">
    <section class="card">
      <h1>🧾 Reporte de Conducta</h1>
      <p class="sub">
        Puedes agregar más de un alumno al mismo reporte. El prefecto se asigna automáticamente.
      </p>

      <div class="docente-badge">
        Docente: <strong><?= htmlspecialchars($nombre_docente) ?></strong>
        <span>(<?= htmlspecialchars($matricula_docente) ?>)</span>
      </div>

      <form id="frmReporte">
        <div id="alumnosCont">
          <div class="alumno-card">
            <label>Matrícula del alumno</label>
            <input name="matricula[]" class="inp-mat" placeholder="Ej. 002 o ROCJ940719A">

            <label>Nombre del alumno</label>
            <input name="nombre[]" class="inp-nom" placeholder="Escribe el nombre completo">
            <small class="msg muted"></small>

            <div class="row">
              <div>
                <label>Grado</label>
                <select name="grado[]" class="sel-grado">
                  <option value="">Grado</option>
                  <option>1</option><option>2</option><option>3</option>
                </select>
              </div>
              <div>
                <label>Grupo</label>
                <select name="grupo[]" class="sel-grupo">
                  <option value="">Grupo</option>
                  <option>A</option><option>B</option><option>C</option><option>D</option><option>E</option>
                </select>
              </div>
            </div>

            <label>Prefecto asignado (auto)</label>
            <input name="prefecto[]" class="prefecto" readonly placeholder="Se asignará según grado y grupo">
          </div>
        </div>

        <button type="button" class="btn btn-green" id="btnAdd">+ Agregar otro alumno</button>

        <div class="row grid-2">
          <div>
            <label>Clase</label>
            <select id="clase" required>
              <option value="">Selecciona</option>
              <option>Español</option><option>Matemáticas</option><option>Ciencias</option>
              <option>Tecnología</option><option>Inglés</option><option>Historia</option>
              <option>Geografía</option><option>Formación Cívica</option><option>Artes</option>
              <option>Educación Física</option><option>Tutoría</option><option>Otra</option>
            </select>
          </div>
          <div>
            <label>Hora</label>
            <select id="hora" required>
              <option value="">Selecciona</option>
              <option>1ra</option><option>2da</option><option>3ra</option>
              <option>4ta</option><option>5ta</option><option>6ta</option>
              <option>7ma</option><option>8va</option><option>9na</option>
              <option>1er Descanso</option><option>2do Descanso</option>
            </select>
          </div>
        </div>

        <label>Descripción de la conducta</label>
        <textarea id="contenido" required placeholder="Describe el incidente con claridad."></textarea>

        <div class="row end">
          <button type="reset" class="btn btn-gray">Limpiar</button>
          <button type="submit" class="btn btn-red">Enviar reporte</button>
        </div>

        <p id="statusMsg" class="muted"></p>
        <p id="reciboLink" style="display:none">
          ✅ Comprobante:
          <a id="aRecibo" target="_blank" rel="noopener">Abrir</a>
        </p>
      </form>
    </section>
  </main>

  <script>
    const DOCENTE = {
      nombre: <?= json_encode($nombre_docente) ?>,
      matricula: <?= json_encode($matricula_docente) ?>
    };
  </script>
  <script src="../js/reporte.js?v=3"></script>
</body>
</html>
