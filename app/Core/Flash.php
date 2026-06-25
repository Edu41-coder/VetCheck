<?php

namespace App\Core;

class Flash
{
    private const KEY = '_flash_messages';

    public static function set(string $type, string $message): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION[self::KEY][$type][] = $message;
    }

    public static function get(): array
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $messages = $_SESSION[self::KEY] ?? [];
        unset($_SESSION[self::KEY]);

        return $messages;
    }

    public static function has(): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return !empty($_SESSION[self::KEY]);
    }
}
