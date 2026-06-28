<?php
/** @var array $counters */
/** @var array|null $user */
?>
<?php $counters = $counters ?? []; ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="h4 mb-1">Compteurs</h2>
        <p class="text-body-secondary mb-0">Suivi des événements récurrents en clinique.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="/Vet_Check/public/dashboard" class="btn btn-outline-secondary btn-sm">Retour</a>
        <?php if (isset($user) && ($user['is_admin'] ?? 0) === 1): ?>
            <a href="/Vet_Check/public/counters/create" class="btn btn-primary">Nouveau compteur</a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-12 col-md-8">
        <label class="form-label" for="counters-search">Recherche</label>
        <input id="counters-search" type="text" class="form-control" placeholder="Rechercher un compteur...">
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label" for="counters-page-size">Lignes par page</label>
        <select id="counters-page-size" class="form-select">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table id="counters-table" class="table table-striped align-middle">
        <thead>
            <tr>
                <th data-sort-index="0">Nom</th>
                <th data-sort-index="1">Événement compté</th>
                <th data-sort-index="2">Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($counters)): ?>
                <tr data-empty="1">
                    <td colspan="4" class="text-center text-body-secondary">Aucun compteur enregistré.</td>
                </tr>
            <?php else: ?>
                <tr data-empty="1" style="display:none;">
                    <td colspan="4" class="text-center text-body-secondary">Aucun compteur enregistré.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($counters as $counter): ?>
                <tr data-row="1">
                    <td><strong><?= htmlspecialchars($counter['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                    <td>
                        <span class="badge text-bg-info text-dark">
                            <?= htmlspecialchars($counter['event_label'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($counter['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/Vet_Check/public/counters/run?id=<?= $counter['id'] ?>"
                               class="btn btn-primary btn-sm">Compter</a>
                            <?php if (isset($user) && ($user['is_admin'] ?? 0) === 1): ?>
                                <a href="/Vet_Check/public/counters/edit?id=<?= $counter['id'] ?>"
                                   class="btn btn-outline-primary btn-sm">Voir / Modifier</a>
                                <a href="/Vet_Check/public/counters/delete?id=<?= $counter['id'] ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Supprimer ce compteur ?');">Supprimer</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$paginationId = 'counters-pagination';
require __DIR__ . '/../partials/pagination.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.VetCheckDatatable && typeof window.VetCheckDatatable.initDatatable === 'function') {
        window.VetCheckDatatable.initDatatable({
            tableId:        'counters-table',
            searchInputId:  'counters-search',
            pageSizeSelectId: 'counters-page-size',
            paginationId:   'counters-pagination',
        });
    }
});
</script>

<div class="mt-4">
    <a href="/Vet_Check/public/counters/history" class="btn btn-outline-secondary">Voir l'historique</a>
</div>
