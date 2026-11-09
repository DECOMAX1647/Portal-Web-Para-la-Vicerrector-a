<?php /* Repositorio público de documentos PDF. */ ?>

<div class="cabecera-repo">
  <h2>Repositorio Académico</h2>
  <p>
    Libros, guías y material de apoyo en formato PDF, disponibles para su descarga.
  </p>
</div>

<section class="lista-documentos">
  <?php if ($documentos === []) { ?>
    <p class="descripcion">Todavía no hay documentos publicados.</p>
  <?php } ?>
  <?php foreach ($documentos as $documento) { ?>
    <article class="documento">
      <div class="documento-superior">
        <span class="documento-icono">
          <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
        </span>
        <div>
          <h3 class="documento-titulo"><?= e($documento['titulo']) ?></h3>
          <p class="documento-autor"><?= e($documento['autor']) ?></p>
        </div>
      </div>

      <p class="documento-descripcion">
        <?= e($documento['descripcion']) ?>
      </p>

      <p class="documento-datos">
        <span><i class="bi bi-tag-fill" aria-hidden="true"></i> <?= e($documento['categoria']) ?></span>
        <span><i class="bi bi-calendar3" aria-hidden="true"></i> <?= e($documento['anio']) ?></span>
      </p>

      <div class="documento-pie">
        <a class="boton boton-primario boton-pequeno" href="<?= e(url_for('/' . $documento['archivo'])) ?>" download>
          <i class="bi bi-download" aria-hidden="true"></i>
          Descargar
        </a>
      </div>
    </article>
  <?php } ?>
</section>
