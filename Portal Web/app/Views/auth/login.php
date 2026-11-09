<?php /* Formulario de acceso. */ ?>

<div class="tarjeta-login">

  <div class="logo">
    <img src="<?= e(url_for('/assets/img/imagenuptp.png')) ?>" alt="Logo UPTP">
  </div>

  <h1 class="titulo-login">Portal de Difusión Académica</h1>
  <p class="texto-bienvenida">Vicerrectoría Académica</p>

  <hr class="divisor">

  <!-- Formulario de acceso -->
  <form action="<?= e(url_for('/login')) ?>" method="post" class="formulario-login" novalidate>
    <?= csrf_field() ?>

    <!-- Mensaje de error -->
    <?php if ($error !== '') { ?>
      <p class="mensaje-error">
        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
        <?= e($error) ?>
      </p>
    <?php } ?>

    <div class="grupo-entrada">
      <label for="usuario">Usuario</label>
      <div class="input-con-icono">
        <input id="usuario" name="usuario" type="text" class="campo-texto"
               value="<?= e($usuarioPrevio) ?>"
               placeholder="Ingrese su usuario" required autocomplete="username">
        <i class="bi bi-person-fill icono-campo"></i>
      </div>
    </div>

    <div class="grupo-entrada">
      <label for="clave">Contraseña</label>
      <div class="input-con-icono">
        <input id="clave" name="clave" type="password" class="campo-texto campo-clave"
               placeholder="Ingrese su contraseña" required autocomplete="current-password">
        <i class="bi bi-lock-fill icono-campo"></i>

        <button type="button" id="btn-clave" class="boton-ojo" aria-label="Mostrar contraseña">
          <i id="icono-ver" class="bi bi-eye"></i>
          <i id="icono-ocultar" class="bi bi-eye-slash oculto"></i>
        </button>
      </div>
    </div>

    <div class="opciones-login">
      <label class="recordarme">
        <input type="checkbox" name="recordar">
        Recordarme
      </label>
      <a href="#" class="enlace-olvido">¿Olvidó su contraseña?</a>
    </div>

    <button type="submit" class="boton-ingresar">Iniciar sesión</button>
  </form>

  <p class="nota-seguridad">
    <i class="bi bi-shield-check"></i>
    Acceso restringido al personal autorizado
  </p>
</div>
