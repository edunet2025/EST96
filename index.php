<?php
  $page_title   = "Escuela Secundaria N.° 77 “República de Panamá”";
  $portada_tit  = "Día de Muertos";
  $portada_desc = "Celebramos nuestras tradiciones.";
  $portada_img  = "/img/noticias/portada.png"; 
  include __DIR__ . "/inc/header.php";
?>

<main id="homepage">

  <!-- Bienvenida -->
  <section class="home-section center-text">
    <h1>🏫 Bienvenidos a la Escuela Secundaria N.º 77 “República de Panamá”</h1>
    <p class="lead">
      ¡Nos alegra contar con tu visita! Explora nuestra comunidad educativa, nuestras actividades 
      y todo lo que tenemos preparado para ti.
    </p>
  </section>

  <!-- Portada del mes -->
  <section class="home-section">
    <article class="home-card">
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
        <a href="https://www.facebook.com/share/1CbnzHeRSw/?mibextid=wwXIfr" target="_blank" rel="noopener">
          <img class="ico" src="/img/facebook.png" alt=""> Facebook
        </a>
        <a href="https://www.instagram.com/" target="_blank" rel="noopener">
          <img class="ico" src="/img/instagram.png" alt=""> Instagram
        </a>
      </div>
    </article>
  </section>

  <!-- Mapa -->
  <section class="home-section">
    <article class="home-card">
      <h3>📍 ¿Cómo llegar?</h3>
      <iframe class="map"
        src="https://www.google.com/maps?q=19.383300843023903,-99.2416994574762&hl=es&z=17&output=embed"
        loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Ubicación de la escuela"></iframe>
      <p class="note">Calle Gregorio López, Fuentes S/N, Santa Fé, Álvaro Obregón, CDMX.</p>
    </article>
  </section>

</main>

<?php include __DIR__ . "/inc/footer.php"; ?>

