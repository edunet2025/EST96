<?php
include("header.php");

// Datos de la portada del mes (puedes cambiar estos valores dinámicamente si luego agregas base de datos)
$portada_tit = "Celebración del Día de Muertos";
$portada_desc = "Nuestros estudiantes participaron en el montaje de altares tradicionales y en la exposición de calaveritas literarias, demostrando creatividad y orgullo por nuestras tradiciones.";
$portada_img = "img/portada_mes.jpg"; // asegúrate de tener esta imagen en /img
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
      <h3><?= $portada_tit ?></h3>
      <p><?= $portada_desc ?></p>
      <img src="<?= $portada_img ?>" alt="<?= $portada_tit ?>" class="banner">
    </article>
  </section>

  <!-- Redes sociales -->
  <section class="home-section">
    <article class="home-card">
      <h3>🎉 Síguenos y conoce nuestras actividades</h3>
      <p class="muted">Publicamos eventos, reconocimientos y avisos importantes para estudiantes y familias.</p>
      <div class="social-row">
        <a href="#" target="_blank" rel="noopener">
          <img class="ico" src="img/facebook.png" alt=""> Facebook
        </a>
        <a href="#" target="_blank" rel="noopener">
          <img class="ico" src="img/instagram.png" alt=""> Instagram
        </a>
      </div>
    </article>
  </section>

  <!-- Mapa -->
  <section class="home-section">
    <article class="home-card">
      <h3>📍 ¿Cómo llegar?</h3>
      <iframe class="map"
        src="https://maps.app.goo.gl/HF9RXMi4mjFNEcxt9"
        loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Ubicación de la escuela"></iframe>
      <p class="note">Guadalupe Victoria 60, Ampliacion San Miguel Ajusco, Tlalpan, 14710 Ciudad de México, CDMX</p>
    </article>
  </section>

</main>

<?php include("footer.php"); ?>
