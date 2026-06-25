<?php

namespace App\Core;

class Config
{
    private static array $items = [];

    public static function load(string $key, string $path): void
    {
        self::$items[$key] = require $path;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$items[$key] ?? $default;
    }
}
