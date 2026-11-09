<!DOCTYPE html>
<html lang="es" class="panel-html">
<head>
  <?php require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
  <div class="contenedor contenedor-panel">

    <p class="barra-superior">
      <span>República Bolivariana de Venezuela</span>
      <span>Ministerio del Poder Popular para la Educación Universitaria</span>
    </p>

    <!-- Cabecera -->
    <header class="cabecera">
      <img src="<?= e(url_for('/assets/img/imagenuptp.png')) ?>" alt="Logo UPTP" class="cabecera-logo">
      <div class="cabecera-texto">
        <h1>Panel de Administración</h1>
        <p class="subtitulo">Vicerrectoría Académica · UPTP</p>
      </div>

      <div class="acciones-cuenta">
        <?= boton_tema() ?>
        <a href="<?= e(url_for('/')) ?>" class="boton boton-fantasma">
          <i class="bi bi-house-door-fill" aria-hidden="true"></i>
          Ver portal
        </a>

        <button type="button" class="boton boton-primario" id="btn-cerrar-sesion">
          <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
          Cerrar sesión
        </button>
      </div>
    </header>

    <div class="panel">
      <?= $content ?>
    </div>

    <footer class="pie">
      <strong>Vicerrectoría Académica · UPTP</strong> · Panel de Administración
    </footer>

  </div>

  <!-- Modal de cerrar sesión -->
  <div class="modal" id="modal-cerrar-sesion" aria-hidden="true">
    <div class="modal-capa" data-cerrar-modal></div>

    <form class="modal-caja" role="dialog" aria-modal="true" aria-labelledby="titulo-modal" action="<?= e(url_for('/cerrar-sesion')) ?>" method="post">
      <?= csrf_field() ?>
      <div class="modal-icono">
        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
      </div>
      <h3 id="titulo-modal">¿Cerrar sesión?</h3>
      <p>Se cerrará tu sesión actual. ¿Deseas continuar?</p>
      <div class="modal-acciones">
        <button type="button" class="boton boton-fantasma" data-cerrar-modal>Cancelar</button>
        <button type="submit" class="boton boton-primario">Sí, cerrar sesión</button>
      </div>
    </form>
  </div>

  <!-- Aviso flotante -->
  <?php $flash = $flash ?? null; ?>
  <div class="aviso" id="aviso" role="status" aria-live="polite"
       data-mensaje="<?= e((string) ($flash['mensaje'] ?? '')) ?>"
       data-error="<?= (($flash['tipo'] ?? '') === 'error') ? '1' : '0' ?>">
    <i id="aviso-icono" class="bi bi-check-circle-fill" aria-hidden="true"></i>
    <span id="aviso-texto">Guardado</span>
  </div>

  <script src="<?= e(url_for('/assets/js/tema.js')) ?>"></script>
  <script src="<?= e(url_for('/assets/js/admin.js?v=7')) ?>"></script>
</body>
</html>
