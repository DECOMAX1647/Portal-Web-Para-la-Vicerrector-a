<?php
/* Controlador base: atajos para vistas, redirecciones y JSON. */

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function request(): Request
    {
        return RequestContext::get();
    }

    /** @param array<string, mixed> $data */
    protected function view(string $template, array $data = [], ?string $layout = 'main'): void
    {
        View::render($template, $data, $layout);
    }

    protected function redirect(string $url, int $code = 302): never
    {
        header('Location: ' . $url, true, $code);
        exit;
    }

    /** @param array<string, mixed> $data */
    protected function json(array $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
