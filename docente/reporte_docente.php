<?php
session_start();
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo'], ['docente','prefectura','orientacion'])) {
  header("Location: ../login.php");
  exit;
}
$usuario = $_SESSION['nombre'] . " (" . $_SESSION['usuario'] . ")";
$tipo    = $_SESSION['tipo'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reporte de Conducta | Técnica 96</title>
<link rel="stylesheet" href="../css/reporte.css">
</head>
<body>
<header class="encabezado">
  <img src="../img/logo.png" alt="Logo E.S.T. 96" class="logo">
  <div>
    <h1>Escuela Secundaria Técnica N.º 96</h1>
    <h2>“Miguel Alemán Valdés”</h2>
  </div>
</header>

<main class="contenedor">
<section class="card">
  <h3>🧾 Reporte de Conducta (<?= ucfirst($tipo) ?>)</h3>
  <p><strong><?= ucfirst($tipo) ?>:</strong> <?= htmlspecialchars($usuario) ?></p>

  <form id="frmReporte">
    <label>Docente responsable</label>
    <select id="docente" required><option value="">Cargando...</option></select>

    <div id="alumnosCont">
      <div class="alumno-block">
        <label>Matrícula del alumno</label>
        <input name="matricula[]" class="inp-mat" required>
        <small class="msg"></small>

        <label>Nombre del alumno</label>
        <input name="nombre[]" class="inp-nom" readonly>

        <div class="fila">
          <div><label>Grado</label><select name="grado[]" class="sel-grado"><option>1</option><option>2</option><option>3</option></select></div>
          <div><label>Grupo</label><select name="grupo[]" class="sel-grupo"><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option></select></div>
        </div>
      </div>
    </div>

    <button type="button" id="btnAdd" class="btn verde">+ Agregar otro alumno</button>

    <div class="fila">
      <div><label>Clase</label>
        <select id="clase" required>
          <option>Tecnología</option><option>Español</option><option>Matemáticas</option>
          <option>Ciencias</option><option>Inglés</option><option>Historia</option>
          <option>Educación Física</option><option>Artes</option>
        </select>
      </div>
      <div><label>Hora</label>
        <select id="hora" required>
          <option>1ra</option><option>2da</option><option>3ra</option><option>4ta</option><option>5ta</option>
        </select>
      </div>
    </div>

    <label>Descripción de la conducta</label>
    <textarea id="contenido" required></textarea>

    <div class="botonera">
      <button type="reset" class="btn beige">Limpiar</button>
      <button type="submit" class="btn vino">Enviar reporte</button>
    </div>
    <p id="statusMsg"></p>
    <p id="reciboLink" style="display:none">✅ Comprobante: <a id="aRecibo" target="_blank">Abrir</a></p>
  </form>
</section>
</main>

<script>
const API_DOCENTES="../backend/api/alumnos/listar_docentes.php";
const API_BUSCAR="../backend/api/alumnos/buscar_por_matricula.php";
const API_GUARDAR="../backend/api/reportes/guardar.php";

async function cargarDocentes(){
  const sel=document.getElementById("docente");
  try{
    const r=await fetch(API_DOCENTES); const d=await r.json();
    sel.innerHTML='<option value="">Selecciona</option>';
    d.forEach(x=>sel.innerHTML+=`<option value="${x.usuario}">${x.nombre_completo}</option>`);
  }catch(e){sel.innerHTML='<option>Error</option>';}
}
cargarDocentes();

function attachLookup(b){
  const m=b.querySelector(".inp-mat"),n=b.querySelector(".inp-nom"),
        g=b.querySelector(".sel-grado"),h=b.querySelector(".sel-grupo"),msg=b.querySelector(".msg");
  m.addEventListener("blur",async()=>{
    const mat=m.value.trim(); if(!mat)return;
    msg.textContent="Buscando...";
    try{
      const r=await fetch(API_BUSCAR+"?matricula="+encodeURIComponent(mat));
      if(!r.ok){msg.textContent="No encontrado";n.value="";return;}
      const a=await r.json(); n.value=a.nombre_completo||""; g.value=a.grado||_
