<?php
include("header.php");

// Leer portada desde JSON
$dataFile = "data/portada.json";
if (file_exists($dataFile)) {
  $portada = json_decode(file_get_contents($dataFile), true);
  $portada_tit = $portada["titulo"] ?? "Sin título";
  $portada_desc = $portada["descripcion"] ?? "Sin descripción";
  $portada_img = $portada["imagen"] ?? "img/portada_default.jpg";
} else {
  $portada_tit = "Portada no disponible";
  $portada_desc = "No hay información de portada.";
  $portada_img = "img/portada_default.jpg";
}
?>

<main class="main-content">

  <!-- Bienvenida -->
  <section class="home-section center-text">
    <h1>🏫 Bienvenidos a la Escuela Secundaria Técnica N.º 96 “Miguel Alemán Valdés”</h1>
    <p class="lead">
      ¡Nos alegra contar con tu visita! Explora nuestra comunidad educativa, nuestras actividades 
      y todo lo que tenemos preparado para ti.
    </p>
  </section>

  <!-- Portada del mes -->
  <section class="home-section">
    <article class="home-card portada">
      <span class="badge">📅 Portada del mes</span>
      <h3><?= htmlspecialchars($portada_tit) ?></h3>
      <p><?= htmlspecialchars($portada_desc) ?></p>
      <img src="<?= htmlspecialchars($portada_img) ?>" alt="<?= htmlspecialchars($portada_tit) ?>" class="banner">
    </article>
  </section>

  <!-- Redes sociales -->
  <section class="home-section">
    <article class="home-card">
      <h3>🎉 Síguenos y conoce nuestras actividades</h3>
      <p class="muted">Publicamos eventos, reconocimientos y avisos importantes para estudiantes y familias.</p>
      <div class="social-row">
        <a href="#" target="_blank"><img class="ico" src="img/facebook.png" alt=""> Facebook</a>
        <a href="#" target="_blank"><img class="ico" src="img/instagram.png" alt=""> Instagram</a>
      </div>
    </article>
  </section>

  <!-- Mapa -->
  <section class="home-section">
    <article class="home-card">
      <h3>📍 ¿Cómo llegar?</h3>
      <iframe class="map"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3763.352466113045!2d-99.2085206256678!3d19.39640514286108!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1ff7a23cb03a3%3A0x9f8376e716dff3b!2sEscuela%20Secundaria%20T%C3%A9cnica%20No.%2096%20Miguel%20Alem%C3%A1n%20Vald%C3%A9s!5e0!3m2!1ses!2smx!4v1698334476015!5m2!1ses!2smx"
        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      <p class="note">Col. san miguel Ajusco, Alcaldía Tlalpan, CDMX</p>
    </article>
  </section>

</main>

<?php include("footer.php"); ?>
