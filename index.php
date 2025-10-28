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
        src="https://www.google.com/maps/place/Escuela+Secundaria+T%C3%A9cnica+N%C2%B0+96+%22Miguel+Alem%C3%A1n+Vald%C3%A9z%22/@19.221645,-99.2060071,16.05z/data=!4m6!3m5!1s0x85cdfdec74cc96c5:0xa0c9d7b319997d60!8m2!3d19.2219757!4d-99.2069223!16s%2Fg%2F1wrgkh7j?hl=es-419&entry=ttu&g_ep=EgoyMDI1MTAyMi4wIKXMDSoASAFQAw%3D%3D"
        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      <p class="note">Col. san miguel Ajusco, Alcaldía Tlalpan, CDMX</p>
    </article>
  </section>

</main>

<?php include("footer.php"); ?>
