<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($path),
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): void
    {
        $path = $this->normalizePath($request->path());
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                [$controllerClass, $action] = $route['handler'];
                $controller = new $controllerClass();
                $controller->{$action}($request);
                return;
            }
        }

        http_response_code(404);
        echo '404 - Page not found';
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        return $path === '//' ? '/' : rtrim($path, '/') ?: '/';
    }
}
