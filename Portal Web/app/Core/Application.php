<?php
/* Aplicación: ejecuta el Router y muestra una página simple si hay error. */

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\HttpException;
use Throwable;

final class Application
{
    public function __construct(
        private readonly Router $router,
        private readonly Request $request,
        private readonly array $config,
    ) {
    }

    public function run(): void
    {
        RequestContext::set($this->request);

        try {
            $this->router->dispatch($this->request);
        } catch (HttpException $e) {
            http_response_code($e->getStatusCode());
            if ($this->config['debug'] ?? false) {
                echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
            } else {
                echo $this->simpleErrorPage($e->getStatusCode());
            }
        } catch (Throwable $e) {
            http_response_code(500);
            if ($this->config['debug'] ?? false) {
                throw $e;
            }
            echo $this->simpleErrorPage(500);
        }
    }

    private function simpleErrorPage(int $code): string
    {
        return match ($code) {
            404 => '<!DOCTYPE html><html lang="es"><head><meta charset="utf-8"><title>404</title></head><body><h1>404 — No encontrado</h1><p><a href="' . \e(\url_for('/')) . '">Volver al inicio</a></p></body></html>',
            default => '<!DOCTYPE html><html lang="es"><head><meta charset="utf-8"><title>Error</title></head><body><h1>Error del servidor</h1></body></html>',
        };
    }
}
