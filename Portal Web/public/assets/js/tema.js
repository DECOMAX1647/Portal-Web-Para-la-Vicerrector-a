/* Cambio entre tema oscuro y claro, recordado en el navegador. */
(function () {
  var raiz = document.documentElement;

  /* Tema que está activo ahora mismo. */
  function temaActual() {
    return raiz.dataset.tema === 'claro' ? 'claro' : 'oscuro';
  }

  /* Pone el ícono, el texto y el estado de todos los botones. */
  function pintarBotones() {
    var claro = temaActual() === 'claro';

    document.querySelectorAll('[data-tema-toggle]').forEach(function (boton) {
      var icono = boton.querySelector('i');
      var texto = boton.querySelector('[data-tema-texto]');

      if (icono) {
        icono.className = claro ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill';
      }
      if (texto) {
        texto.textContent = claro ? 'Modo oscuro' : 'Modo claro';
      }

      boton.setAttribute('aria-pressed', claro ? 'true' : 'false');
      boton.setAttribute('aria-label', claro ? 'Cambiar a modo oscuro' : 'Cambiar a modo claro');
    });

    /* Barra del navegador móvil. */
    var meta = document.querySelector('meta[name="theme-color"]');
    if (meta) {
      meta.setAttribute('content', claro ? '#f5f1ec' : '#151413');
    }
  }

  /* Alterna el tema y lo guarda. */
  function alternar() {
    var nuevo = temaActual() === 'claro' ? 'oscuro' : 'claro';
    raiz.dataset.tema = nuevo;

    try {
      localStorage.setItem('tema', nuevo);
    } catch (e) {
      /* Si el navegador bloquea el almacenamiento, igual cambia el tema. */
    }

    pintarBotones();
  }

  document.addEventListener('click', function (evento) {
    var boton = evento.target.closest('[data-tema-toggle]');
    if (boton) {
      alternar();
    }
  });

  pintarBotones();
})();
