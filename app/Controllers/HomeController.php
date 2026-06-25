<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $this->render('home/index', [
            'title' => 'VetCheck',
        ]);
    }
}
