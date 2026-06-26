<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Config;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Security;

class AuthController extends Controller
{
    public function redirectToLogin(Request $request): void
    {
        header('Location: ' . Config::get('app')['base_url'] . '/login');
        exit;
    }

    public function showLogin(Request $request): void
    {
        Security::requireGuest();

        $this->render('auth/login', [
            'title' => 'Connexion',
            'csrf_token' => Security::csrfToken(),
        ]);
    }

    public function login(Request $request): void
    {
        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');
        $token = $request->input('_csrf');

        if (!Security::validateCsrf($token)) {
            Flash::set('error', 'Jeton CSRF invalide. Veuillez réessayer.');
            header('Location: ' . Config::get('app')['base_url'] . '/login');
            exit;
        }

        if (Auth::attempt($email, $password)) {
            Flash::set('success', 'Connexion réussie.');
            header('Location: ' . Config::get('app')['base_url'] . '/dashboard');
            exit;
        }

        Flash::set('error', 'Adresse email ou mot de passe incorrect.');
        header('Location: ' . Config::get('app')['base_url'] . '/login');
        exit;
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        Flash::set('success', 'Vous êtes bien déconnecté.');
        header('Location: ' . Config::get('app')['base_url'] . '/login');
        exit;
    }
}
