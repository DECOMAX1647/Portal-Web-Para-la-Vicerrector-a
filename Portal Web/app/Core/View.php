<?php
/* Vistas: convierte "carpeta/archivo" en un .php y lo envuelve en un layout. */

declare(strict_types=1);

namespace App\Core;

final class View
{
    private static ?string $root = null;

    public static function setViewsPath(string $path): void
    {
        self::$root = rtrim($path, DIRECTORY_SEPARATOR);
    }

    /** @param array<string, mixed> $data */
    public static function render(string $template, array $data = [], ?string $layout = 'main'): void
    {
        if (self::$root === null) {
            self::$root = dirname(__DIR__, 2) . '/app/Views';
        }

        extract($data, EXTR_SKIP);

        $templateFile = self::$root . '/' . str_replace('.', '/', $template) . '.php';
        if (!is_file($templateFile)) {
            throw new \RuntimeException('Vista no encontrada: ' . $template);
        }

        ob_start();
        include $templateFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;

            return;
        }

        $layoutFile = self::$root . '/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            echo $content;

            return;
        }

        include $layoutFile;
    }
}
