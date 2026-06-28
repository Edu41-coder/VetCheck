<?php
/** @var array $user */
/** @var int $userCount */
/** @var int $counterCount */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-1">Espace admin</h2>
        <p class="text-body-secondary mb-0">Bienvenue, <?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>. Gérez ici les utilisateurs, checklists et compteurs de la clinique.</p>
    </div>
    <a href="/Vet_Check/public/dashboard" class="btn btn-outline-secondary btn-sm">Retour</a>
</div>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="app-admin-icon bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002A.274.274 0 0 1 15 13H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="h6 mb-0">Gestion des utilisateurs</h3>
                    <p class="text-body-secondary small mb-0"><?= $userCount ?> utilisateur<?= $userCount > 1 ? 's' : '' ?> enregistré<?= $userCount > 1 ? 's' : '' ?></p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/Vet_Check/public/admin/users" class="btn btn-primary btn-sm">Voir tous les utilisateurs</a>
                <a href="/Vet_Check/public/admin/users/create" class="btn btn-outline-primary btn-sm">Créer un utilisateur</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="app-admin-icon bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="h6 mb-0">Gestion des checklists</h3>
                    <p class="text-body-secondary small mb-0">Créer, modifier et supprimer les checklists</p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/Vet_Check/public/checklists" class="btn btn-success btn-sm">Voir les checklists</a>
                <a href="/Vet_Check/public/checklists/create" class="btn btn-outline-success btn-sm">Créer une checklist</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="app-admin-icon bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M9 13a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-.5-4a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm5.646 5.854a.5.5 0 0 0-.707-.707L10.207 7.5h-4.414l.646.646a.5.5 0 1 0 .707-.707l-1.5-1.5a.5.5 0 0 0-.707 0l-1.5 1.5a.5.5 0 1 0 .707.707L5.793 7.5h4.414l-.646-.646a.5.5 0 0 0-.707.707l1.5 1.5a.5.5 0 0 0 .707 0l1.5-1.5Z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="h6 mb-0">Gestion des compteurs</h3>
                    <p class="text-body-secondary small mb-0"><?= $counterCount ?> compteur<?= $counterCount > 1 ? 's' : '' ?> enregistré<?= $counterCount > 1 ? 's' : '' ?></p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/Vet_Check/public/counters" class="btn btn-info btn-sm">Voir les compteurs</a>
                <a href="/Vet_Check/public/counters/create" class="btn btn-outline-info btn-sm">Créer un compteur</a>
            </div>
        </div>
    </div>
</div>
