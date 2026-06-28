<?php
/** @var array $counter */
/** @var array $instance */
/** @var array $items */
/** @var array $dailyCountsMap */
/** @var array $totalCountsMap */
/** @var array $entriesByItem */
/** @var string $selectedDate */
/** @var array|null $user */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="h4 mb-1"><?= htmlspecialchars($counter['name'], ENT_QUOTES, 'UTF-8') ?></h2>
        <p class="text-body-secondary mb-0">
            Événement : <strong><?= htmlspecialchars($counter['event_label'], ENT_QUOTES, 'UTF-8') ?></strong>
        </p>
    </div>
    <a href="/Vet_Check/public/counters" class="btn btn-outline-secondary btn-sm">Retour</a>
</div>

<!-- Sélection de date -->
<form method="get" action="/Vet_Check/public/counters/run" class="row g-2 mb-4">
    <input type="hidden" name="id" value="<?= (int) $counter['id'] ?>">
    <div class="col-12 col-md-4">
        <label class="form-label" for="run_date">Date</label>
        <input type="date" id="run_date" name="date" class="form-control"
               value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="col-12 col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100">Changer la date</button>
    </div>
</form>

<!-- Items groupés par section -->
<div class="card shadow-sm">
    <div class="card-body">
        <?php if (empty($items)): ?>
            <div class="alert alert-info mb-0">Aucun événement disponible dans ce compteur.</div>
        <?php endif; ?>

        <?php $lastSection = null; ?>
        <?php foreach ($items as $item): ?>
            <?php
            $section   = $item['section_title'] ?? 'Sans section';
            $itemId    = (int) $item['id'];
            $dailyCount = $dailyCountsMap[$itemId] ?? 0;
            $totalCount = $totalCountsMap[$itemId] ?? 0;
            $todayEntries = $entriesByItem[$itemId] ?? [];
            ?>

            <?php if ($section !== $lastSection): ?>
                <h4 class="h6 mt-4 mb-2 text-primary-emphasis">
                    <?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8') ?>
                </h4>
                <?php $lastSection = $section; ?>
            <?php endif; ?>

            <div class="border rounded-3 p-3 mb-3">
                <!-- Titre + compteurs -->
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                    <div class="fw-semibold"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge text-bg-success">
                            Aujourd'hui : <?= $dailyCount ?>
                        </span>
                        <span class="badge text-bg-primary">
                            Total : <?= $totalCount ?>
                        </span>
                    </div>
                </div>

                <!-- Bouton +1 -->
                <form method="post" action="/Vet_Check/public/counters/add-count" class="d-inline">
                    <input type="hidden" name="instance_id" value="<?= (int) $instance['id'] ?>">
                    <input type="hidden" name="item_id"     value="<?= $itemId ?>">
                    <button class="btn btn-success btn-sm">+&nbsp;1</button>
                </form>

                <!-- Détail des comptages du jour -->
                <?php if (!empty($todayEntries)): ?>
                    <div class="mt-2">
                        <div class="small text-body-secondary mb-1">Comptages du jour :</div>
                        <div class="d-flex flex-wrap gap-1">
                            <?php foreach ($todayEntries as $entry): ?>
                                <span class="badge text-bg-light text-dark border">
                                    <?= htmlspecialchars($entry['user_name'], ENT_QUOTES, 'UTF-8') ?>
                                    &mdash;
                                    <?= htmlspecialchars(
                                        date('H:i', strtotime($entry['counted_at'])),
                                        ENT_QUOTES, 'UTF-8'
                                    ) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
