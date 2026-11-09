<!DOCTYPE html>
<html lang="es">
<head>
  <?php require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
  <div class="contenedor">

    <p class="barra-superior">
      <span>República Bolivariana de Venezuela</span>
      <span>Ministerio del Poder Popular para la Educación Universitaria</span>
    </p>

    <!-- Cabecera y menú -->
    <header class="cabecera cabecera-portal">
      <div class="cabecera-fila">
        <img src="<?= e(url_for('/assets/img/imagenuptp.png')) ?>" alt="Logo UPTP" class="cabecera-logo">
        <div class="cabecera-texto">
          <h1>Portal de Difusión de Información Académica para la Vicerrectoría Académica</h1>
          <p class="subtitulo">Universidad Politécnica Territorial · UPTP</p>
        </div>
      </div>

      <div class="cabecera-fila">
        <nav class="navegacion" aria-label="Navegación principal">
          <a href="<?= e(url_for('/')) ?>" class="enlace-menu<?= menu_activo('inicio', $zona ?? '') ?>">Inicio</a>
          <a href="<?= e(url_for('/repositorio')) ?>" class="enlace-menu<?= menu_activo('repositorio', $zona ?? '') ?>">Repositorio</a>
        </nav>

        <div class="acciones-cuenta">
          <?= boton_tema() ?>
          <a href="<?= e(url_for('/login')) ?>" class="boton boton-primario">
            <i class="bi bi-person-fill" aria-hidden="true"></i>
            Iniciar sesión
          </a>
        </div>
      </div>
    </header>

    <main>
      <?= $content ?>
    </main>

    <!-- Pie de página -->
    <footer class="pie">
      <strong>Vicerrectoría Académica · UPTP</strong> · Portal de Difusión Académica
    </footer>

  </div>

  <script src="<?= e(url_for('/assets/js/tema.js')) ?>"></script>
</body>
</html>
