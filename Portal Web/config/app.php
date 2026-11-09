<?php
/* Configuración general de la aplicación. */

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'Portal UPTP',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim($_ENV['APP_URL'] ?? '', '/'),
    'base_path' => trim((string) ($_ENV['APP_BASE_PATH'] ?? ''), '/'),
];
