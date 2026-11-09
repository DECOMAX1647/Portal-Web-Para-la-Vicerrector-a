<?php /* Portada, anuncios e información institucional. */ ?>

<!-- Portada -->
<section class="portada">
  <div class="portada-texto">
    <span class="etiqueta etiqueta-rojo portada-etiqueta">Vicerrectoría Académica</span>
    <h2 class="portada-titulo">Un espacio para la comunidad universitaria</h2>
    <p class="portada-parrafo">
      Este portal reúne la información que produce la Vicerrectoría Académica para docentes,
      estudiantes y personal administrativo. Aquí encontrará los comunicados oficiales, el
      repositorio de libros y material de apoyo en PDF, y los recursos de consulta de la
      universidad.
    </p>
    <p class="portada-parrafo">
      Nuestro objetivo es acercar la información académica a toda la comunidad y mantenerla
      disponible de forma clara, ordenada y permanente.
    </p>
  </div>

  <aside class="portada-panel">
    <h2>Nuestras funciones</h2>
    <ul class="lista-funciones">
      <li>
        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
        Coordinar los programas y planes de estudio.
      </li>
      <li>
        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
        Promover la investigación y la extensión.
      </li>
      <li>
        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
        Gestionar el repositorio de material académico.
      </li>
    </ul>
  </aside>
</section>

<!-- Anuncios -->
<section class="seccion">
  <div class="cabecera-seccion">
    <h2 class="titulo-seccion">Anuncios recientes</h2>
    <p class="subtitulo-seccion">Comunicados oficiales de la Vicerrectoría</p>
  </div>

  <div class="lista-anuncios">
    <?php if ($anuncios === []) { ?>
      <p class="descripcion">Todavía no hay anuncios publicados.</p>
    <?php } ?>
    <?php foreach ($anuncios as $anuncio) { ?>
      <article class="anuncio">
        <div class="anuncio-cabecera">
          <span class="etiqueta <?= e($anuncio['etiqueta']) ?>"><?= e($anuncio['categoria']) ?></span>
          <span class="anuncio-fecha"><?= e($anuncio['fecha']) ?></span>
        </div>
        <h3 class="anuncio-titulo"><?= e($anuncio['titulo']) ?></h3>
        <p class="anuncio-texto"><?= e($anuncio['texto']) ?></p>
        <p class="anuncio-pie">Publicado por · <?= e($anuncio['autor']) ?></p>
      </article>
    <?php } ?>
  </div>
</section>

<!-- Sobre la Vicerrectoría -->
<section class="seccion">
  <div class="cabecera-seccion">
    <h2 class="titulo-seccion">Sobre la Vicerrectoría Académica</h2>
  </div>

  <div class="tarjetas-info">
    <article class="tarjeta tarjeta-info">
      <span class="icono-info icono-rojo">
        <i class="bi bi-patch-check-fill" aria-hidden="true"></i>
      </span>
      <h3>Misión</h3>
      <p>
        Garantizar la calidad de los procesos académicos mediante la planificación,
        organización y evaluación de los programas de formación de la universidad.
      </p>
    </article>

    <article class="tarjeta tarjeta-info">
      <span class="icono-info icono-blanco">
        <i class="bi bi-eye-fill" aria-hidden="true"></i>
      </span>
      <h3>Visión</h3>
      <p>
        Ser un referente en la gestión académica universitaria, apoyando la innovación
        educativa y la difusión del conocimiento de forma abierta y accesible.
      </p>
    </article>

    <article class="tarjeta tarjeta-info">
      <span class="icono-info icono-amarillo">
        <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>
      </span>
      <h3>Funciones</h3>
      <p>
        Coordinar los planes de estudio, apoyar la formación docente, gestionar el
        repositorio académico y divulgar la información institucional.
      </p>
    </article>
  </div>
</section>
