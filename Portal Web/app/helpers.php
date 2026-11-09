<?php
/* Funciones de ayuda para vistas y enlaces. */

declare(strict_types=1);

/** Escapa texto para mostrarlo seguro en HTML. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Prefijo de URL (subcarpeta), detectado automáticamente. */
function app_url_prefix(): string
{
    return \App\Core\BasePath::prefix();
}

/** Enlace interno que respeta subcarpetas. */
function url_for(string $path = '/'): string
{
    $path = \App\Core\BasePath::normalize($path);
    $prefix = app_url_prefix();
    if ($path === '/') {
        return $prefix === '' ? '/' : $prefix . '/';
    }

    return $prefix . $path;
}

/** Marca el enlace activo del menú. */
function menu_activo(string $zona, string $actual): string
{
    return $zona === $actual ? ' activo' : '';
}

/** Token contra envíos externos de formularios. */
function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(?string $token): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $session = $_SESSION['_csrf'] ?? '';

    return is_string($token) && $session !== '' && hash_equals($session, $token);
}

/** Botón para cambiar entre tema oscuro y claro. */
function boton_tema(bool $flotante = false): string
{
    $clases = 'boton boton-fantasma boton-tema' . ($flotante ? ' boton-tema-flotante' : '');

    return '<button type="button" class="' . $clases . '" data-tema-toggle aria-pressed="false" aria-label="Cambiar a modo claro">'
        . '<i class="bi bi-sun-fill" aria-hidden="true"></i>'
        . '<span data-tema-texto>Modo claro</span>'
        . '</button>';
}

/** Fecha larga en español: 02 de octubre, 2026. */
function fecha_es(?string $fecha): string
{
    if ($fecha === null || $fecha === '') {
        return '';
    }

    $ts = strtotime($fecha);
    if ($ts === false) {
        return $fecha;
    }

    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
    ];

    return date('d', $ts) . ' de ' . $meses[(int) date('n', $ts)] . ', ' . date('Y', $ts);
}

/** Fecha y hora cortas para la bitácora. */
function fecha_hora_es(?string $fecha): string
{
    if ($fecha === null || $fecha === '') {
        return '';
    }

    $ts = strtotime($fecha);

    return $ts === false ? $fecha : date('d/m/Y H:i', $ts);
}

/** Guarda un mensaje para mostrarlo después de redirigir. */
function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

/** Lee y borra el mensaje guardado. */
function flash_obtener(): ?array
{
    $mensaje = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return is_array($mensaje) ? $mensaje : null;
}
