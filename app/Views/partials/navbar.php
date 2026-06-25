<?php use App\Core\Auth; ?>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="/Vet_Check/public/">
            <img src="/Vet_Check/logo.png" alt="VetCheck" class="app-logo-navbar">
            <span>VetCheck</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar" aria-controls="appNavbar" aria-expanded="false" aria-label="Basculer la navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (Auth::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/checklists">Checklists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/checklists/history">Historique</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/Vet_Check/public/">Accueil</a>
                </li>
                <?php if (Auth::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/logout">Déconnexion</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link disabled"><?= htmlspecialchars(Auth::user()['name'], ENT_QUOTES, 'UTF-8') ?></span>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/login">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
