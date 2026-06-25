<?php
/** @var array $checklists */
/** @var array|null $user */
?>
<?php $checklists = $checklists ?? []; ?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="h4 mb-1">Checklists</h2>
        <p class="text-body-secondary mb-0">Gestion des listes de contrôle cliniques.</p>
    </div>
    <?php if (isset($user) && ($user['is_admin'] ?? 0) === 1): ?>
        <a href="/Vet_Check/public/checklists/create" class="btn btn-primary">Nouvelle checklist</a>
    <?php endif; ?>
</div>

<div class="row g-2 mb-3">
    <div class="col-12 col-md-8">
        <label class="form-label" for="checklists-search">Recherche</label>
        <input id="checklists-search" type="text" class="form-control" placeholder="Rechercher une checklist...">
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label" for="checklists-page-size">Lignes par page</label>
        <select id="checklists-page-size" class="form-select">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table id="checklists-table" class="table table-striped align-middle">
        <thead>
            <tr>
                <th data-sort-index="0">Nom</th>
                <th data-sort-index="1">Description</th>
                <th data-sort-index="2">Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($checklists)): ?>
                <tr data-empty="1">
                    <td colspan="4" class="text-center text-body-secondary">Aucune checklist enregistrée.</td>
                </tr>
            <?php else: ?>
                <tr data-empty="1" style="display:none;">
                    <td colspan="4" class="text-center text-body-secondary">Aucune checklist enregistrée.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($checklists as $checklist): ?>
                <tr data-row="1">
                    <td>
                        <strong><?= htmlspecialchars($checklist['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </td>
                    <td><?= htmlspecialchars($checklist['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><span class="small"><?= htmlspecialchars($checklist['slug'], ENT_QUOTES, 'UTF-8') ?></span></td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/Vet_Check/public/checklists/run?id=<?= $checklist['id'] ?>" class="btn btn-primary btn-sm">Exécuter</a>
                            <a href="/Vet_Check/public/checklists/edit?id=<?= $checklist['id'] ?>" class="btn btn-outline-primary btn-sm">Voir / Modifier</a>
                            <?php if (isset($user) && ($user['is_admin'] ?? 0) === 1): ?>
                                <a href="/Vet_Check/public/checklists/delete?id=<?= $checklist['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cette checklist ?');">Supprimer</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$paginationId = 'checklists-pagination';
require __DIR__ . '/../partials/pagination.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.VetCheckDatatable && typeof window.VetCheckDatatable.initDatatable === 'function') {
        window.VetCheckDatatable.initDatatable({
            tableId: 'checklists-table',
            searchInputId: 'checklists-search',
            pageSizeSelectId: 'checklists-page-size',
            paginationId: 'checklists-pagination'
        });
    }
});
</script>

<div class="mt-4">
    <a href="/Vet_Check/public/checklists/history" class="btn btn-outline-secondary">Voir l'historique</a>
</div>
