<?php

namespace App\Core;

class Security
{
    private const CSRF_KEY = '_csrf_token';

    public static function requireAuth(): void
    {
        if (!Auth::check()) {
            Flash::set('warning', 'Vous devez être connecté pour accéder à cette page.');
            header('Location: ' . Config::get('app')['base_url'] . '/login');
            exit;
        }
    }

    public static function requireGuest(): void
    {
        if (Auth::check()) {
            header('Location: ' . Config::get('app')['base_url'] . '/dashboard');
            exit;
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireAuth();

        if (!Auth::hasAnyRole($roles)) {
            http_response_code(403);
            echo '<div class="container py-5"><div class="alert alert-danger">Accès refusé : vous n\'avez pas les permissions nécessaires.</div></div>';
            exit;
        }
    }

    public static function csrfToken(): string
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION[self::CSRF_KEY])) {
            $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::CSRF_KEY];
    }

    public static function validateCsrf(?string $token): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($token) || empty($_SESSION[self::CSRF_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::CSRF_KEY], $token);
    }
}
