<?php
/* <head> compartido: metadatos, fuentes, iconos y estilos. */
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Portal de Difusión de Información Académica de la Vicerrectoría Académica de la UPTP: anuncios, repositorio de documentos y recursos institucionales.">
<meta name="theme-color" content="#151413">
<link rel="icon" type="image/png" href="<?= e(url_for('/assets/img/imagenuptp.png')) ?>">
<title><?= e($titulo ?? 'Portal UPTP') ?></title>

<link rel="stylesheet" href="<?= e(url_for('/assets/css/fuentes.css?v=5')) ?>">
<link rel="stylesheet" href="<?= e(url_for('/assets/icons/bootstrap-icons.css')) ?>">
<link rel="stylesheet" href="<?= e(url_for('/assets/css/tema.css?v=15')) ?>">
<link rel="stylesheet" href="<?= e(url_for($cssVista ?? '/assets/css/publico.css?v=17')) ?>">

<script>
  /* Aplica el tema guardado antes de pintar (evita el destello). */
  try { var t = localStorage.getItem('tema'); if (t) document.documentElement.dataset.tema = t; } catch (e) {}
</script>
