<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Security;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $this->render('home/index', [
            'title' => 'VetCheck',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
            ],
        ]);
    }

    public function dashboard(Request $request): void
    {
        Security::requireAuth();

        $this->render('home/dashboard', [
            'title' => 'Tableau de bord',
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
            ],
        ]);
    }

    public function adminPanel(Request $request): void
    {
        Security::requireRole(['admin']);

        $this->render('home/admin', [
            'title' => 'Administration',
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Administration', 'url' => '/Vet_Check/public/admin'],
            ],
        ]);
    }

    public function vetoArea(Request $request): void
    {
        Security::requireRole(['veto']);

        $this->render('home/veto', [
            'title' => 'Espace vétérinaire',
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Espace vétérinaire', 'url' => '/Vet_Check/public/veto'],
            ],
        ]);
    }

    public function asvArea(Request $request): void
    {
        Security::requireRole(['asv']);

        $this->render('home/asv', [
            'title' => 'Espace ASV',
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => '/Vet_Check/public/'],
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Espace ASV', 'url' => '/Vet_Check/public/asv'],
            ],
        ]);
    }
}
