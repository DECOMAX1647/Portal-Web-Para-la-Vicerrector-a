<?php
/* Detecta el prefijo de URL (subcarpeta) para que los enlaces funcionen
   igual en Apache, nginx o con `php -S`. */

declare(strict_types=1);

namespace App\Core;

final class BasePath
{
    private static ?string $prefix = null;

    public static function reset(): void
    {
        self::$prefix = null;
    }

    /* Prefijo sin barra final: "", "/MVC" o "/MVC/public". */
    public static function prefix(): string
    {
        if (self::$prefix !== null) {
            return self::$prefix;
        }

        $fromEnv = trim((string) ($_ENV['APP_BASE_PATH'] ?? ''), '/');
        if ($fromEnv !== '') {
            self::$prefix = '/' . $fromEnv;

            return self::$prefix;
        }

        self::$prefix = self::detect();

        return self::$prefix;
    }

    /* Quita el prefijo de la ruta para que el Router vea "/", "/panel", etc. */
    public static function strip(string $path): string
    {
        $path = self::normalize($path);

        foreach (self::candidatePrefixes() as $prefix) {
            if ($prefix === '') {
                continue;
            }
            if ($path === $prefix) {
                return '/';
            }
            if (str_starts_with($path, $prefix . '/')) {
                $path = self::normalize(substr($path, strlen($prefix)));
                break;
            }
        }

        return self::stripFrontController($path);
    }

    public static function normalize(string $path): string
    {
        $path = '/' . trim(str_replace('\\', '/', $path), '/');
        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }

        return $path;
    }

    /* /index.php o /index.php/panel → / o /panel */
    public static function stripFrontController(string $path): string
    {
        if ($path === '/index.php') {
            return '/';
        }
        if (str_starts_with($path, '/index.php/')) {
            return self::normalize(substr($path, strlen('/index.php')));
        }

        return $path;
    }

    /** @return list<string> */
    private static function candidatePrefixes(): array
    {
        $prefix = self::prefix();
        $list = $prefix !== '' ? [$prefix] : [];

        $scriptDir = self::scriptDir();
        if ($scriptDir !== '' && !in_array($scriptDir, $list, true)) {
            $list[] = $scriptDir;
        }
        if (str_ends_with($scriptDir, '/public')) {
            $parent = substr($scriptDir, 0, -strlen('/public'));
            if ($parent !== '' && $parent !== '/' && !in_array($parent, $list, true)) {
                $list[] = $parent;
            }
        }

        usort($list, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        return $list;
    }

    private static function detect(): string
    {
        $scriptDir = self::scriptDir();
        $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $uriPath = self::normalize($uriPath);

        if ($scriptDir === '') {
            return '';
        }

        if ($uriPath === $scriptDir || str_starts_with($uriPath, $scriptDir . '/')) {
            return $scriptDir;
        }

        if (str_ends_with($scriptDir, '/public')) {
            $parent = substr($scriptDir, 0, -strlen('/public'));
            if ($parent !== '' && $parent !== '/' && ($uriPath === $parent || str_starts_with($uriPath, $parent . '/'))) {
                return $parent;
            }
        }

        return $scriptDir;
    }

    private static function scriptDir(): string
    {
        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        $dir = str_replace('\\', '/', dirname($script));
        if ($dir === '/' || $dir === '.' || $dir === '\\') {
            return '';
        }

        return rtrim($dir, '/');
    }
}
