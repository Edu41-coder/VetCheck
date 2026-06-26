<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Security;

class HomeController extends Controller
{
    public function dashboard(Request $request): void
    {
        Security::requireAuth();

        $this->render('home/dashboard', [
            'title' => 'Tableau de bord',
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
            ],
        ]);
    }

}
