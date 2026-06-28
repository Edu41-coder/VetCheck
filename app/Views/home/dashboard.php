<div class="mb-4">
    <h2 class="h4 mb-1">Tableau de bord</h2>
    <p class="text-body-secondary mb-0">Bienvenue, <strong><?= htmlspecialchars($user['name'] ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></strong> &mdash; <?= htmlspecialchars($user['role_name'] ?? '', ENT_QUOTES, 'UTF-8') ?><?= !empty($user['is_admin']) ? ' &middot; <span class="text-primary fw-semibold">Privilège admin</span>' : '' ?>.</p>
</div>

<h3 class="h6 text-body-secondary text-uppercase mb-3" style="letter-spacing:.05em;font-size:.75rem;">Accès rapide</h3>
<div class="row g-3 mb-4">
    <!-- Checklists -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="/Vet_Check/public/checklists" class="text-decoration-none">
            <div class="card h-100 p-3 text-center app-quicklink-card">
                <div class="app-quicklink-icon bg-primary bg-opacity-10 text-primary rounded-3 mx-auto mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3.854 2.146a.5.5 0 0 0-.708 0l-1.5 1.5-.5-.5a.5.5 0 1 0-.707.707l.853.854a.5.5 0 0 0 .707 0l1.853-1.854a.5.5 0 0 0 0-.707zm0 4a.5.5 0 0 0-.708 0l-1.5 1.5-.5-.5a.5.5 0 1 0-.707.707l.853.854a.5.5 0 0 0 .707 0l1.853-1.854a.5.5 0 0 0 0-.707zm0 4a.5.5 0 0 0-.708 0l-1.5 1.5-.5-.5a.5.5 0 0 0-.707.707l.853.854a.5.5 0 0 0 .707 0l1.853-1.854a.5.5 0 0 0 0-.707z"/>
                    </svg>
                </div>
                <span class="fw-semibold small">Checklists</span>
            </div>
        </a>
    </div>

    <!-- Compteurs -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="/Vet_Check/public/counters" class="text-decoration-none">
            <div class="card h-100 p-3 text-center app-quicklink-card">
                <div class="app-quicklink-icon bg-success bg-opacity-10 text-success rounded-3 mx-auto mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M9 13a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-.5-4a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm5.646 5.854a.5.5 0 0 0-.707-.707L10.207 7.5h-4.414l.646.646a.5.5 0 1 0 .707-.707l-1.5-1.5a.5.5 0 0 0-.707 0l-1.5 1.5a.5.5 0 1 0 .707.707L5.793 7.5h4.414l-.646-.646a.5.5 0 0 0-.707.707l1.5 1.5a.5.5 0 0 0 .707 0l1.5-1.5Z"/>
                    </svg>
                </div>
                <span class="fw-semibold small">Compteurs</span>
            </div>
        </a>
    </div>

    <?php if (!empty($user['is_admin'])): ?>
    <!-- Espace admin -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="/Vet_Check/public/admin" class="text-decoration-none">
            <div class="card h-100 p-3 text-center app-quicklink-card">
                <div class="app-quicklink-icon bg-warning bg-opacity-10 text-warning rounded-3 mx-auto mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    </svg>
                </div>
                <span class="fw-semibold small">Espace admin</span>
            </div>
        </a>
    </div>
    <?php endif; ?>
</div>
