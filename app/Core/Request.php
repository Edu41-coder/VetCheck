<?php

namespace App\Core;

class Request
{
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $parsedPath = parse_url($uri, PHP_URL_PATH);
        $path = $parsedPath !== false && $parsedPath !== null && $parsedPath !== '' ? $parsedPath : '/';
        $basePath = rtrim(Config::get('app')['base_url'] ?? '', '/');

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        $path = '/' . ltrim($path, '/');

        if ($path === '//') {
            return '/';
        }

        $trimmed = rtrim($path, '/');
        return $trimmed !== '' ? $trimmed : '/';
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
}
