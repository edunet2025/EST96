<?php
if (!isset($_SESSION)) session_start();
?>
<header class="encabezado">
  <div class="logo-escuela">
    <img src="../img/logo.png" alt="Logo Técnica 96">
    <h1>Técnica 96 “Miguel Alemán Valdés”</h1>
  </div>

  <nav class="nav">
    <button class="menu-toggle" id="menuToggle">☰</button>
    <ul id="menuList">
      <li><a href="menu-docente.php">Inicio</a></li>
      <li><a href="reporte_docente.php">📝 Reportes</a></li>
      <li><a href="../logout.php" class="logout">Salir</a></li>
    </ul>
  </nav>
</header>

<script>
document.getElementById("menuToggle").addEventListener("click", () => {
  document.getElementById("menuList").classList.toggle("open");
});
</script>
