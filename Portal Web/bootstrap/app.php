<?php
/* Arranque: autoload, .env, configuración, rutas, petición y sesión. */

declare(strict_types=1);

use App\Core\Application;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;

$root = dirname(__DIR__);

// 1) Autoload: Composer si está instalado; si no, PSR-4 propio (sin dependencias).
$vendor = $root . '/vendor/autoload.php';
if (is_file($vendor)) {
    require $vendor;
} else {
    spl_autoload_register(static function (string $class) use ($root): void {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }
        $relative = substr($class, strlen($prefix));
        $file = $root . '/app/' . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require $file;
        }
    });
    require $root . '/app/helpers.php';
}

// 2) Variables de entorno (.env).
$envFile = $root . '/.env';
if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, " \t\"'");
        $_ENV[$name] = $value;
        putenv("{$name}={$value}");
    }
}

// 3) Configuración y modo depuración.
$appConfig = require $root . '/config/app.php';

if (!$appConfig['debug']) {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// 4) Base de datos (la conexión se abre solo si se usa).
Database::boot(require $root . '/config/database.php');

// 5) Rutas.
$router = new Router();
$register = require $root . '/routes/web.php';
if (is_callable($register)) {
    $register($router);
}

// 6) Petición actual.
$request = Request::fromGlobals();

// 7) Sesión para el login y el panel.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

return new Application($router, $request, $appConfig);
