<?php
/* Enrutador: relaciona método + URL con un controlador. */

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\HttpException;

final class Router
{
    /** @var list<array{methods: list<string>, pattern: string, regex: string, paramNames: list<string>, handler: string}> */
    private array $routes = [];

    public function get(string $pattern, string $handler): void
    {
        $this->addRoute(['GET'], $pattern, $handler);
    }

    public function post(string $pattern, string $handler): void
    {
        $this->addRoute(['POST'], $pattern, $handler);
    }

    /** @param list<string> $methods */
    public function match(array $methods, string $pattern, string $handler): void
    {
        $this->addRoute($methods, $pattern, $handler);
    }

    /** @param list<string> $methods */
    private function addRoute(array $methods, string $pattern, string $handler): void
    {
        $pattern = '/' . trim($pattern, '/');
        if ($pattern !== '/') {
            $pattern = rtrim($pattern, '/') ?: '/';
        }

        $paramNames = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $m) use (&$paramNames): string {
            $paramNames[] = $m[1];

            return '([^/]+)';
        }, $pattern);

        if ($regex === null) {
            throw new \InvalidArgumentException('Patrón de ruta inválido: ' . $pattern);
        }

        $this->routes[] = [
            'methods' => array_map('strtoupper', $methods),
            'pattern' => $pattern,
            'regex' => '#^' . $regex . '$#',
            'paramNames' => $paramNames,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): void
    {
        foreach ($this->routes as $route) {
            if (!in_array($request->method, $route['methods'], true)) {
                continue;
            }
            if (!preg_match($route['regex'], $request->path, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route['paramNames'] as $i => $name) {
                $params[$name] = $matches[$i + 1] ?? null;
            }

            $this->invokeHandler($route['handler'], $params);

            return;
        }

        throw new HttpException(404, 'Ruta no encontrada: ' . $request->path);
    }

    /** @param array<string, mixed> $params */
    private function invokeHandler(string $handler, array $params): void
    {
        if (!str_contains($handler, '@')) {
            throw new \InvalidArgumentException('Handler debe ser Clase@metodo: ' . $handler);
        }
        [$class, $method] = explode('@', $handler, 2);
        $fqcn = str_starts_with($class, '\\') ? $class : 'App\\Controllers\\' . $class;

        if (!class_exists($fqcn)) {
            throw new HttpException(500, 'Controlador no encontrado: ' . $fqcn);
        }

        $controller = new $fqcn();

        if (!method_exists($controller, $method)) {
            throw new HttpException(500, 'Método no encontrado: ' . $fqcn . '::' . $method);
        }

        $controller->{$method}(...$params);
    }
}
