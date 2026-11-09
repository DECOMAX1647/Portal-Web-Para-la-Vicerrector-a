/* Mostrar u ocultar la contraseña. */

(function () {
  var btn = document.getElementById('btn-clave');
  var input = document.getElementById('clave');
  var ver = document.getElementById('icono-ver');
  var ocultar = document.getElementById('icono-ocultar');

  if (!btn || !input) return;

  btn.addEventListener('click', function () {
    var mostrar = input.type === 'password';
    input.type = mostrar ? 'text' : 'password';
    btn.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña');
    if (ver) ver.classList.toggle('oculto', mostrar);
    if (ocultar) ocultar.classList.toggle('oculto', !mostrar);
  });
})();
