<?php
$restringidoA = "docente"; 
include("../verificar_sesion.php");
require_once("../conexion.php");

// Actualizar última actividad
if (isset($_SESSION['usuario'])) {
  $stmt = $conn->prepare("UPDATE usuarios SET ultima_actividad = NOW() WHERE usuario = ?");
  $stmt->bind_param("s", $_SESSION['usuario']);
  $stmt->execute();
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel Docente | Técnica 96</title>

  <!-- CSS -->
  <link rel="stylesheet" href="css/header.css?v=1">
  <link rel="stylesheet" href="css/menu-docente.css?v=1">
</head>
<body>

  <?php include("header.php"); ?>

  <main class="contenido-menu">
    <p class="saludo">
      👋 Hola, <strong><?= htmlspecialchars($_SESSION['nombre']); ?></strong>.  
      Bienvenido(a) al panel docente de la Escuela Secundaria Técnica N.º 96 “Miguel Alemán Valdés”.
    </p>

    <!-- ====== RESUMEN GENERAL ====== -->
    <section class="panel-resumen">
      <h2>📊 Resumen de tu actividad</h2>
      <div class="tarjetas-resumen">
        <div class="tarjeta vino">🧾 Exámenes creados<br><span id="totalExamenes">—</span></div>
        <div class="tarjeta beige">💬 Comentarios<br><span id="totalComentarios">—</span></div>
        <div class="tarjeta cafe">⚠️ Reportes<br><span id="totalReportes">—</span></div>
        <div class="tarjeta gris">🕒 Asistencias<br><span id="totalAsistencias">—</span></div>
        <div class="tarjeta verde">📂 Actividades<br><span id="totalActividades">—</span></div>
      </div>
    </section>

    <!-- ====== DATOS PERSONALES ====== -->
    <section class="panel-datos">
      <h2>👤 Información del docente</h2>
      <div class="foto-docente">
        <img id="fotoDocente" src="../img/SAPM960412.PNG" alt="Foto del docente">
      </div>

      <div class="datos-grid">
        <div class="dato"><strong>Usuario:</strong> <span><?= htmlspecialchars($_SESSION['usuario']); ?></span></div>
        <div class="dato"><strong>Nombre:</strong> <span><?= htmlspecialchars($_SESSION['nombre']); ?></span></div>
        <div class="dato"><strong>Academia:</strong> <span>Tecnologías</span></div>
        <div class="dato"><strong>Disciplina:</strong> <span>PCIA</span></div>
      </div>
    </section>

    <!-- ====== ACCESOS RÁPIDOS ====== -->
    <section class="panel-accesos">
      <h2>⚡ Accesos rápidos</h2>
      <div class="botones-acceso">
        <a href="examen.php" class="btn vino">➕ Crear Examen</a>
        <a href="reporte_docente.php" class="btn cafe">📝 Crear Reporte</a>
        <a href="../comun/consulta_alumnos.php" class="btn beige">🔍 Buscar Alumno</a>
        <a href="../logout.php" class="btn gris">🚪 Cerrar Sesión</a>
      </div>
    </section>
  </main>

  <footer class="pie">
    © Escuela Secundaria Técnica N.º 96 “Miguel Alemán Valdés” — Academia de Tecnologías PCIA
  </footer>

  <script src="js/panel-docente.js?v=1"></script>
</body>
</html>
