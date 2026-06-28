<?php
/** @var array $rows */
/** @var array $users */
/** @var array $counters */
/** @var array $filters */
/** @var string $chartUrl */

$currentSortBy  = (string) ($filters['sort_by']  ?? 'counted_at');
$currentSortDir = strtolower((string) ($filters['sort_dir'] ?? 'desc'));

$buildSortUrl = static function (string $column) use ($filters, $currentSortBy, $currentSortDir): string {
    $nextDir = ($currentSortBy === $column && $currentSortDir === 'asc') ? 'desc' : 'asc';
    return '/Vet_Check/public/counters/history?' . http_build_query([
        'user_id'    => (string) ($filters['user_id']    ?? ''),
        'counter_id' => (string) ($filters['counter_id'] ?? ''),
        'date_from'  => (string) ($filters['date_from']  ?? ''),
        'date_to'    => (string) ($filters['date_to']    ?? ''),
        'sort_by'    => $column,
        'sort_dir'   => $nextDir,
    ]);
};

$sortIndicator = static function (string $column) use ($currentSortBy, $currentSortDir): string {
    if ($currentSortBy !== $column) return '↕';
    return $currentSortDir === 'asc' ? '↑' : '↓';
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h2 class="h4 mb-1">Historique des comptages</h2>
        <p class="text-body-secondary mb-0">Filtrez par utilisateur, compteur et plage de dates.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= htmlspecialchars($chartUrl, ENT_QUOTES, 'UTF-8') ?>"
           class="btn btn-outline-primary btn-sm">
            📊 Vue graphique
        </a>
        <a href="/Vet_Check/public/counters" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>
</div>

<!-- Recherche + taille de page -->
<div class="row g-2 mb-3">
    <div class="col-12 col-md-8">
        <label class="form-label" for="history-search">Recherche</label>
        <input id="history-search" type="text" class="form-control"
               placeholder="Rechercher un événement, utilisateur, compteur…">
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

<!-- Filtres -->
<form method="get" action="/Vet_Check/public/counters/history" class="row g-2 mb-4">
    <div class="col-12 col-md-3">
        <label class="form-label" for="user_id">Utilisateur</label>
        <select id="user_id" name="user_id" class="form-select">
            <option value="">Tous</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= (int) $u['id'] ?>"
                    <?= ((string) ($filters['user_id'] ?? '') === (string) $u['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label" for="counter_id">Compteur</label>
        <select id="counter_id" name="counter_id" class="form-select">
            <option value="">Tous</option>
            <?php foreach ($counters as $c): ?>
                <option value="<?= (int) $c['id'] ?>"
                    <?= ((string) ($filters['counter_id'] ?? '') === (string) $c['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 col-md-2">
        <label class="form-label" for="date_from">Du</label>
        <input id="date_from" type="date" name="date_from"
               value="<?= htmlspecialchars((string) ($filters['date_from'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
               class="form-control">
    </div>
    <div class="col-12 col-md-2">
        <label class="form-label" for="date_to">Au</label>
        <input id="date_to" type="date" name="date_to"
               value="<?= htmlspecialchars((string) ($filters['date_to'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
               class="form-control">
    </div>
    <div class="col-12 col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<!-- Tableau -->
<div class="table-responsive">
    <table id="history-table" class="table table-striped align-middle">
        <thead>
            <tr>
                <th>
                    <a href="<?= htmlspecialchars($buildSortUrl('date'), ENT_QUOTES, 'UTF-8') ?>"
                       class="btn btn-sm btn-link p-0 text-decoration-none text-dark fw-semibold">
                        Date <?= htmlspecialchars($sortIndicator('date'), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </th>
                <th>
                    <a href="<?= htmlspecialchars($buildSortUrl('counter'), ENT_QUOTES, 'UTF-8') ?>"
                       class="btn btn-sm btn-link p-0 text-decoration-none text-dark fw-semibold">
                        Compteur <?= htmlspecialchars($sortIndicator('counter'), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </th>
                <th>
                    <a href="<?= htmlspecialchars($buildSortUrl('item'), ENT_QUOTES, 'UTF-8') ?>"
                       class="btn btn-sm btn-link p-0 text-decoration-none text-dark fw-semibold">
                        Événement <?= htmlspecialchars($sortIndicator('item'), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </th>
                <th>
                    <a href="<?= htmlspecialchars($buildSortUrl('user'), ENT_QUOTES, 'UTF-8') ?>"
                       class="btn btn-sm btn-link p-0 text-decoration-none text-dark fw-semibold">
                        Utilisateur <?= htmlspecialchars($sortIndicator('user'), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </th>
                <th>
                    <a href="<?= htmlspecialchars($buildSortUrl('counted_at'), ENT_QUOTES, 'UTF-8') ?>"
                       class="btn btn-sm btn-link p-0 text-decoration-none text-dark fw-semibold">
                        Horodatage <?= htmlspecialchars($sortIndicator('counted_at'), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr data-empty="1">
                    <td colspan="5" class="text-center text-body-secondary">Aucun comptage trouvé.</td>
                </tr>
            <?php else: ?>
                <tr data-empty="1" style="display:none;">
                    <td colspan="5" class="text-center text-body-secondary">Aucun comptage trouvé.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($rows as $row): ?>
                <tr data-row="1">
                    <td><?= htmlspecialchars($row['counter_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['counter_name'],  ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['item_title'],    ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['user_name'],     ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['counted_at'],    ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$paginationId = 'counters-history-pagination';
require __DIR__ . '/../partials/pagination.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.VetCheckDatatable && typeof window.VetCheckDatatable.initDatatable === 'function') {
        window.VetCheckDatatable.initDatatable({
            tableId:          'history-table',
            searchInputId:    'history-search',
            pageSizeSelectId: 'history-page-size',
            paginationId:     'counters-history-pagination',
        });
    }
});
</script>
