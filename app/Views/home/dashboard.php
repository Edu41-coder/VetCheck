<div class="mb-4">
    <h2 class="h4">Tableau de bord</h2>
    <p class="text-body-secondary">Bienvenue, <?= htmlspecialchars($user['name'] ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?>.</p>
</div>
<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card p-3 h-100">
            <h3 class="h6">Accès rapide</h3>
            <ul class="list-unstyled mb-0">
                <li><a href="/Vet_Check/public/admin">Zone admin</a></li>
                <li><a href="/Vet_Check/public/veto">Zone vétérinaire</a></li>
                <li><a href="/Vet_Check/public/asv">Zone ASV</a></li>
            </ul>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card p-3 h-100">
            <h3 class="h6">Informations</h3>
            <p class="mb-0">Rôle principal : <strong><?= htmlspecialchars($user['role_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong></p>
            <p class="mb-0">Admin : <strong><?= (isset($user['is_admin']) && $user['is_admin']) ? 'Oui' : 'Non' ?></strong></p>
        </div>
    </div>
</div>
