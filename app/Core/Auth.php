<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    private const SESSION_KEY = 'auth_user';

    public static function attempt(string $email, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        self::login($user);

        return true;
    }

    public static function login(array $user): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        unset($user['password_hash']);
        $_SESSION[self::SESSION_KEY] = $user;
    }

    public static function logout(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        unset($_SESSION[self::SESSION_KEY]);
    }

    public static function user(): ?array
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function roleName(): ?string
    {
        $user = self::user();
        return $user['role_name'] ?? null;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return (int) ($user['is_admin'] ?? 0) === 1;
    }

    public static function hasRole(string $role): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        $role = strtolower(trim($role));
        $currentRole = strtolower($user['role_name'] ?? '');

        if ($role === 'admin') {
            return self::isAdmin();
        }

        return $currentRole === $role;
    }

    public static function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if (self::hasRole($role)) {
                return true;
            }
        }

        return false;
    }
}
