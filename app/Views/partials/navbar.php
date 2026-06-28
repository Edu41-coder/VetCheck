<?php use App\Core\Auth; ?>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="/Vet_Check/public/">
            <img src="/Vet_Check/logo.png" alt="VetCheck" class="app-logo-navbar">
            <span>VetCheck</span>
        </a>
        <?php if (Auth::check()): ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar" aria-controls="appNavbar" aria-expanded="false" aria-label="Basculer la navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php endif; ?>
        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (Auth::check()): ?>
                    <?php if (Auth::isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/admin">Admin</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/checklists">Checklists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/counters">Compteurs</a>
                    </li>
                <?php endif; ?>
                <?php if (Auth::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Vet_Check/public/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link btn btn-link d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1" aria-hidden="true" focusable="false">
                                <path fill-rule="evenodd" d="M10.146 11.354a.5.5 0 0 1 0-.708L12.293 8.5H6.5a.5.5 0 0 1 0-1h5.793l-2.147-2.146a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0z"/>
                                <path fill-rule="evenodd" d="M13 14.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5v-3a.5.5 0 0 1 1 0v3a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 4.5 0h7A1.5 1.5 0 0 1 13 1.5v13z"/>
                            </svg>
                            <span>Déconnexion</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link disabled d-inline-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1" aria-hidden="true" focusable="false">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                <path d="M14 14s-1-4-6-4-6 4-6 4 1 1 6 1 6-1 6-1z"/>
                            </svg>
                            <span><?= htmlspecialchars(Auth::user()['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if (Auth::check()): ?>
    <div class="modal fade app-modal" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutConfirmModalLabel">Confirmer la déconnexion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    Voulez-vous vraiment vous déconnecter ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="/Vet_Check/public/logout" class="btn btn-danger">Se déconnecter</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
