<!DOCTYPE html>
<html lang="es">
<head>
  <?php require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>

  <div class="fondo-login" aria-hidden="true"></div>

  <?= boton_tema(true) ?>

  <main class="contenedor-login">
    <div class="columna-login">
      <?= $content ?>

      <!-- Pie de página -->
      <p class="pie-pagina">© 2026 UPTP · Vicerrectoría Académica</p>
    </div>
  </main>

  <script src="<?= e(url_for('/assets/js/tema.js')) ?>"></script>
  <script src="<?= e(url_for('/assets/js/login.js')) ?>"></script>
</body>
</html>
