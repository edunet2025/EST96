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
<!-- 📍 Cómo llegar -->
<section class="home-section">
  <article class="home-card">
    <h3>📍 ¿Cómo llegar?</h3>

    <iframe
      class="map"
      src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7301.01881193531!2d-99.20600714049877!3d19.221645029117624!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cdfdec74cc96c5%3A0xa0c9d7b319997d60!2sEscuela%20Secundaria%20T%C3%A9cnica%20N%C2%B0%2096%20%22Miguel%20Alem%C3%A1n%20Vald%C3%A9z%22!5e0!3m2!1ses-419!2smx!4v1761619340037!5m2!1ses-419!2smx"
      loading="lazy"
      allowfullscreen
      referrerpolicy="no-referrer-when-downgrade"
      title="Ubicación de la Escuela Secundaria Técnica N.º 96 'Miguel Alemán Valdés'">
    </iframe>

    <p class="note">📍 Guadalupe Victoria 60, Ampliacion San Miguel Ajusco, Tlalpan, 14710 Ciudad de México, CDMX</p>
  </article>
</section>

</main>

<?php include("footer.php"); ?>
