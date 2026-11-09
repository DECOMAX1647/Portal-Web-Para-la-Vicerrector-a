<?php /* Panel: pestañas con radios, menú lateral y cinco vistas. */ ?>

<?php $vista = $vista ?? 'dashboard'; ?>

<!-- Pestañas: los radios ocultos controlan qué panel se ve -->
<input type="radio" name="vista" id="vista-dashboard" class="radio-vista" <?= $vista === 'dashboard' ? 'checked' : '' ?>>
<input type="radio" name="vista" id="vista-repo" class="radio-vista" <?= $vista === 'repo' ? 'checked' : '' ?>>
<input type="radio" name="vista" id="vista-anuncios" class="radio-vista" <?= $vista === 'anuncios' ? 'checked' : '' ?>>
<input type="radio" name="vista" id="vista-bitacora" class="radio-vista" <?= $vista === 'bitacora' ? 'checked' : '' ?>>
<input type="radio" name="vista" id="vista-perfil" class="radio-vista" <?= $vista === 'perfil' ? 'checked' : '' ?>>

<!-- Menú lateral -->
<aside class="menu-lateral">
  <p class="menu-titulo">Administración</p>

  <label for="vista-dashboard" class="opcion-menu">
    <i class="bi bi-speedometer2" aria-hidden="true"></i>
    Dashboard
  </label>

  <label for="vista-repo" class="opcion-menu">
    <i class="bi bi-folder-fill" aria-hidden="true"></i>
    Repositorio (PDFs)
  </label>

  <label for="vista-anuncios" class="opcion-menu">
    <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
    Anuncios
  </label>

  <label for="vista-bitacora" class="opcion-menu">
    <i class="bi bi-journal-text" aria-hidden="true"></i>
    Bitácora
  </label>

  <label for="vista-perfil" class="opcion-menu">
    <i class="bi bi-person-circle" aria-hidden="true"></i>
    Mi Perfil
  </label>
</aside>

<main class="contenido">

  <!-- Dashboard -->
  <section id="panel-dashboard" class="vista">
    <h2>Dashboard</h2>
    <p class="descripcion">Resumen general del portal de difusión académica.</p>

    <div class="estadisticas">
      <div class="estadistica">
        <p class="estadistica-valor rojo"><?= count($documentos) ?></p>
        <p class="estadistica-etiqueta">Documentos en el repositorio</p>
      </div>
      <div class="estadistica">
        <p class="estadistica-valor amarillo"><?= count($anuncios) ?></p>
        <p class="estadistica-etiqueta">Anuncios publicados</p>
      </div>
      <div class="estadistica">
        <p class="estadistica-valor"><?= number_format((int) ($visitas ?? 0), 0, ',', '.') ?></p>
        <p class="estadistica-etiqueta">Personas que han visto el portal</p>
      </div>
    </div>
  </section>

  <!-- Repositorio (subir PDFs) -->
  <section id="panel-repo" class="vista">
    <h2>Subir documentos al repositorio</h2>
    <p class="descripcion">Los PDFs que suba aquí aparecerán en la página pública de Repositorio.</p>

    <form class="formulario-admin" id="form-pdf"
          action="<?= e(url_for('/panel/documentos')) ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="campo">
        <label for="pdf-titulo">Título del documento</label>
        <input id="pdf-titulo" name="titulo" type="text" placeholder="Ej: Introducción al Álgebra Lineal" required>
      </div>

      <div class="fila-formulario">
        <div class="campo">
          <label for="pdf-autor">Autor / docente</label>
          <input id="pdf-autor" name="autor" type="text" placeholder="Ej: Prof. Luis Martínez" required>
        </div>
        <div class="campo">
          <label for="pdf-categoria">Categoría</label>
          <input id="pdf-categoria" name="categoria" type="text" placeholder="Ej: Matemática" required>
        </div>
      </div>

      <div class="campo campo-corto">
        <label for="pdf-anio">Año</label>
        <input id="pdf-anio" name="anio" type="number" min="1990" max="2100" placeholder="2026" required>
      </div>

      <div class="campo">
        <label for="pdf-descripcion">Descripción</label>
        <textarea id="pdf-descripcion" name="descripcion" placeholder="Breve descripción del contenido del documento…"></textarea>
      </div>

      <div class="campo">
        <label for="pdf-archivo">Archivo PDF</label>
        <div class="archivo">
          <i class="bi bi-paperclip" aria-hidden="true"></i>
          <input id="pdf-archivo" name="archivo" type="file" accept="application/pdf" required>
        </div>
        <p class="archivo-nombre" id="pdf-archivo-nombre"></p>
      </div>

      <div>
        <button type="submit" class="boton boton-primario">
          <i class="bi bi-cloud-arrow-up-fill" aria-hidden="true"></i>
          Subir al repositorio
        </button>
      </div>
    </form>

    <h3 class="subcabecera">Documentos publicados</h3>

    <?php if ($documentos === []) { ?>
      <p class="descripcion">Todavía no hay documentos publicados.</p>
    <?php } else { ?>
      <div class="lista" id="lista-pdfs">
        <?php foreach ($documentos as $documento) { ?>
          <div class="elemento">
            <span class="elemento-icono">
              <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
            </span>
            <div class="elemento-datos">
              <h4><?= e($documento['titulo']) ?></h4>
              <p><?= e($documento['autor']) ?> · <?= e($documento['categoria']) ?> · <?= e($documento['anio']) ?></p>
            </div>
            <div class="elemento-acciones">
              <form method="post" data-confirmar="¿Eliminar este documento?"
                    action="<?= e(url_for('/panel/documentos/' . (int) $documento['id'] . '/eliminar')) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="boton boton-peligro boton-pequeno">Eliminar</button>
              </form>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </section>

  <!-- Anuncios -->
  <section id="panel-anuncios" class="vista">
    <h2>Publicar un anuncio</h2>
    <p class="descripcion">El anuncio aparecerá en la página de Inicio del portal público.</p>

    <form class="formulario-admin" id="form-anuncio"
          action="<?= e(url_for('/panel/anuncios')) ?>" method="post">
      <?= csrf_field() ?>

      <div class="campo campo-corto">
        <label for="anuncio-categoria">Categoría</label>
        <select id="anuncio-categoria" name="categoria">
          <option value="Académico">Académico</option>
          <option value="Convocatoria">Convocatoria</option>
          <option value="Aviso">Aviso</option>
          <option value="Evento">Evento</option>
        </select>
      </div>

      <div class="campo">
        <label for="anuncio-titulo">Título del anuncio</label>
        <input id="anuncio-titulo" name="titulo" type="text" placeholder="Ej: Inscripciones abiertas para el próximo lapso" required>
      </div>

      <div class="campo">
        <label for="anuncio-contenido">Contenido</label>
        <textarea id="anuncio-contenido" name="texto" placeholder="Escriba aquí el texto del anuncio…" required></textarea>
      </div>

      <div>
        <button type="submit" class="boton boton-primario">
          <i class="bi bi-send-fill" aria-hidden="true"></i>
          Publicar anuncio
        </button>
      </div>
    </form>

    <h3 class="subcabecera">Anuncios publicados</h3>

    <?php if ($anuncios === []) { ?>
      <p class="descripcion">Todavía no hay anuncios publicados.</p>
    <?php } else { ?>
      <div class="lista" id="lista-anuncios">
        <?php foreach ($anuncios as $anuncio) { ?>
          <div class="elemento">
            <span class="elemento-icono amarillo">
              <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
            </span>
            <div class="elemento-datos">
              <h4><?= e($anuncio['titulo']) ?></h4>
              <p><?= e($anuncio['texto']) ?></p>
            </div>
            <div class="elemento-acciones">
              <form method="post" data-confirmar="¿Eliminar este anuncio?"
                    action="<?= e(url_for('/panel/anuncios/' . (int) $anuncio['id'] . '/eliminar')) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="boton boton-peligro boton-pequeno">Eliminar</button>
              </form>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </section>

  <!-- Bitácora -->
  <section id="panel-bitacora" class="vista">
    <h2>Bitácora</h2>
    <p class="descripcion">Historial de las acciones del panel: quién inició sesión, publicó, subió o eliminó un documento o anuncio.</p>

    <?php if ($bitacora === []) { ?>
      <p class="descripcion">Todavía no hay acciones registradas.</p>
    <?php } else { ?>
      <div class="lista bitacora">
        <?php foreach ($bitacora as $registro) { ?>
          <div class="elemento">
            <span class="elemento-icono">
              <i class="bi bi-clock-history" aria-hidden="true"></i>
            </span>
            <div class="elemento-datos">
              <h4><?= e($registro['accion']) ?></h4>
              <p><?= e($registro['detalle']) ?> · <?= e($registro['usuario']) ?> · <?= e($registro['fecha']) ?></p>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </section>

  <!-- Mi Perfil -->
  <section id="panel-perfil" class="vista">
    <h2>Mi Perfil</h2>
    <p class="descripcion">Datos de la cuenta administrativa.</p>

    <div class="tarjeta tarjeta-perfil">
      <h3><?= e((string) ($perfil['nombre'] ?? $usuarioNombre)) ?></h3>
      <p>Usuario: <?= e((string) ($perfil['usuario'] ?? $usuario)) ?></p>
      <p class="perfil-linea">
        <span class="etiqueta etiqueta-amarillo"><?= e(ucfirst((string) ($perfil['rol'] ?? 'admin'))) ?></span>
      </p>
      <p class="perfil-linea">Rol: Vicerrectoría Académica · Coordinador de Difusión</p>
    </div>
  </section>

</main>
