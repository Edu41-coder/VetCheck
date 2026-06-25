<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function addRoute(string $method, string $path, array $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($path),
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): void
    {
        $path = $this->normalizePath($request->path());
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                foreach ($route['middleware'] as $middleware) {
                    if (is_callable($middleware)) {
                        $middleware($request);
                        continue;
                    }

                    if (is_string($middleware)) {
                        $this->executeMiddleware($middleware);
                    }
                }

                [$controllerClass, $action] = $route['handler'];
                $controller = new $controllerClass();
                $controller->{$action}($request);
                return;
            }
        }

        http_response_code(404);
        echo '404 - Page not found';
    }

    private function executeMiddleware(string $middleware): void
    {
        $parts = explode(':', $middleware, 2);
        $name = $parts[0];
        $param = $parts[1] ?? null;

        switch ($name) {
            case 'auth':
                Security::requireAuth();
                break;
            case 'guest':
                Security::requireGuest();
                break;
            case 'role':
                $roles = array_map('trim', explode(',', $param ?? ''));
                Security::requireRole($roles);
                break;
            case 'roles':
                $roles = array_map('trim', explode(',', $param ?? ''));
                Security::requireRole($roles);
                break;
            default:
                break;
        }
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . ltrim($path, '/');

        if ($path === '//') {
            return '/';
        }

        $trimmed = rtrim($path, '/');
        return $trimmed !== '' ? $trimmed : '/';
    }
}
