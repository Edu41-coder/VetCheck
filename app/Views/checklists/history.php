<?php
/** @var array $rows */
/** @var array $users */
/** @var array $checklists */
/** @var array $filters */
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="h4 mb-1">Historique des validations</h2>
        <p class="text-body-secondary mb-0">Filtre par utilisateur, checklist et plage de dates.</p>
    </div>
    <a href="/Vet_Check/public/checklists" class="btn btn-outline-secondary btn-sm">Retour</a>
</div>

<div class="row g-2 mb-3">
    <div class="col-12 col-md-8">
        <label class="form-label" for="history-search">Recherche</label>
        <input id="history-search" type="text" class="form-control" placeholder="Rechercher une tâche, un utilisateur, une checklist...">
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label" for="history-page-size">Lignes par page</label>
        <select id="history-page-size" class="form-select">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>
    </div>
</div>

<form method="get" action="/Vet_Check/public/checklists/history" class="row g-2 mb-4">
    <div class="col-12 col-md-3">
        <label class="form-label" for="user_id">Utilisateur</label>
        <select id="user_id" name="user_id" class="form-select">
            <option value="">Tous</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= (int) $u['id'] ?>" <?= ((string) ($filters['user_id'] ?? '') === (string) $u['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label" for="checklist_id">Checklist</label>
        <select id="checklist_id" name="checklist_id" class="form-select">
            <option value="">Toutes</option>
            <?php foreach ($checklists as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= ((string) ($filters['checklist_id'] ?? '') === (string) $c['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 col-md-2">
        <label class="form-label" for="date_from">Du</label>
        <input id="date_from" type="date" name="date_from" value="<?= htmlspecialchars((string) ($filters['date_from'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control">
    </div>

    <div class="col-12 col-md-2">
        <label class="form-label" for="date_to">Au</label>
        <input id="date_to" type="date" name="date_to" value="<?= htmlspecialchars((string) ($filters['date_to'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control">
    </div>

    <div class="col-12 col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<div class="table-responsive history-table-wrap">
    <table id="history-table" class="table table-striped align-middle">
        <thead>
            <tr>
                <th data-sort-index="0">Date</th>
                <th data-sort-index="1">Checklist</th>
                <th data-sort-index="2">Tâche</th>
                <th data-sort-index="3">Utilisateur</th>
                <th data-sort-index="4">Horodatage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr data-empty="1">
                    <td colspan="5" class="text-center text-body-secondary">Aucune validation trouvée.</td>
                </tr>
            <?php else: ?>
                <tr data-empty="1" style="display:none;">
                    <td colspan="5" class="text-center text-body-secondary">Aucune validation trouvée.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($rows as $row): ?>
                <tr data-row="1">
                    <td><?= htmlspecialchars($row['checklist_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['checklist_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['task_title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['checked_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$paginationId = 'history-pagination';
require __DIR__ . '/../partials/pagination.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.VetCheckDatatable && typeof window.VetCheckDatatable.initDatatable === 'function') {
        window.VetCheckDatatable.initDatatable({
            tableId: 'history-table',
            searchInputId: 'history-search',
            pageSizeSelectId: 'history-page-size',
            paginationId: 'history-pagination'
        });
    }
});
</script>
