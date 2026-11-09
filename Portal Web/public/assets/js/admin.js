/* Panel: aviso flotante, nombre del PDF y modal de cerrar sesión. */
document.addEventListener("DOMContentLoaded", function () {

  /* Archivo PDF elegido */
  var archivo = document.getElementById("pdf-archivo");
  var archivoNombre = document.getElementById("pdf-archivo-nombre");

  if (archivo && archivoNombre) {
    archivo.addEventListener("change", function () {
      archivoNombre.textContent = archivo.files.length
        ? "Archivo seleccionado: " + archivo.files[0].name
        : "";
    });
  }

  /* Aviso flotante */
  var aviso = document.getElementById("aviso");
  var avisoTexto = document.getElementById("aviso-texto");
  var avisoIcono = document.getElementById("aviso-icono");
  var avisoTimer = null;

  function mostrarAviso(mensaje, esError) {
    if (!aviso) return;
    avisoTexto.textContent = mensaje;
    aviso.classList.toggle("error", !!esError);
    if (avisoIcono) {
      avisoIcono.className = esError
        ? "bi bi-exclamation-triangle-fill"
        : "bi bi-check-circle-fill";
    }
    aviso.classList.add("visible");
    clearTimeout(avisoTimer);
    avisoTimer = setTimeout(function () { aviso.classList.remove("visible"); }, 3000);
  }

  /* Mensaje que dejó el servidor tras crear o eliminar. */
  if (aviso && aviso.dataset.mensaje) {
    mostrarAviso(aviso.dataset.mensaje, aviso.dataset.error === "1");
  }

  /* Confirmación antes de eliminar. */
  document.addEventListener("submit", function (e) {
    var form = e.target.closest("form[data-confirmar]");
    if (form && !window.confirm(form.dataset.confirmar)) e.preventDefault();
  });

  /* Modal de cerrar sesión */
  var modal = document.getElementById("modal-cerrar-sesion");
  var btnAbrir = document.getElementById("btn-cerrar-sesion");

  if (modal && btnAbrir) {
    var abrir = function () {
      modal.classList.add("visible");
      modal.setAttribute("aria-hidden", "false");
    };
    var cerrar = function () {
      modal.classList.remove("visible");
      modal.setAttribute("aria-hidden", "true");
      btnAbrir.focus();
    };

    btnAbrir.addEventListener("click", abrir);

    modal.querySelectorAll("[data-cerrar-modal]").forEach(function (el) {
      el.addEventListener("click", cerrar);
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && modal.classList.contains("visible")) cerrar();
    });
  }
});
