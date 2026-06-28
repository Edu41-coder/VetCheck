<?php
/** @var array $byItem */
/** @var array $byDay */
/** @var array $byUser */
/** @var array $users */
/** @var array $counters */
/** @var array $filters */
/** @var string $historyUrl */

$chartData = json_encode([
    'byItem' => array_values($byItem),
    'byDay'  => array_values($byDay),
    'byUser' => array_values($byUser),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="h4 mb-1">Graphiques — Compteurs</h2>
        <p class="text-body-secondary mb-0">Visualisation des comptages sur la période filtrée.</p>
    </div>
    <a href="<?= htmlspecialchars($historyUrl, ENT_QUOTES, 'UTF-8') ?>"
       class="btn btn-outline-secondary btn-sm">
        ← Retour à l'historique
    </a>
</div>

<!-- Filtres -->
<form method="get" action="/Vet_Check/public/counters/chart" class="row g-2 mb-4">
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
        <button class="btn btn-primary w-100">Appliquer</button>
    </div>
</form>

<!-- Boutons de sélection du type de graphique -->
<div class="d-flex gap-2 mb-3 flex-wrap">
    <button class="btn btn-primary active" data-chart-type="bar">
        ▐▌ Barres
    </button>
    <button class="btn btn-outline-primary" data-chart-type="line">
        📈 Ligne
    </button>
    <button class="btn btn-outline-primary" data-chart-type="donut">
        🍩 Donut
    </button>
</div>

<!-- Légende dynamique -->
<p id="chart-legend" class="text-body-secondary small mb-2">Total par événement</p>

<!-- Canvas Chart.js -->
<div class="card shadow-sm p-3">
    <?php if (empty($byItem) && empty($byDay) && empty($byUser)): ?>
        <div class="alert alert-info mb-0">Aucune donnée à afficher pour cette sélection.</div>
    <?php else: ?>
        <canvas id="counterChart" style="max-height:420px;"></canvas>
    <?php endif; ?>
</div>

<script>
window.VetCheckCounterChartData = <?= $chartData ?>;
document.addEventListener('DOMContentLoaded', function () {
    if (window.VetCheckCharts) {
        window.VetCheckCharts.init(window.VetCheckCounterChartData);
    }
});
</script>
