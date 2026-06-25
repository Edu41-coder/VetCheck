<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Request;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        $this->render('auth/login', [
            'title' => 'Connexion',
        ]);
    }

    public function login(Request $request): void
    {
        Flash::set('info', 'L\'authentification sera branchée à l\'étape suivante.');
        header('Location: /Vet_Check/public/login');
        exit;
    }
}
