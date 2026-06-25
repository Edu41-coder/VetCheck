<?php
/** @var array $checklist */
/** @var array $instance */
/** @var array $tasks */
/** @var array $checksMap */
/** @var string $selectedDate */
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="h4 mb-1"><?= htmlspecialchars($checklist['name'], ENT_QUOTES, 'UTF-8') ?></h2>
        <p class="text-body-secondary mb-0">Exécution du jour avec traçabilité des validations.</p>
    </div>
    <a href="/Vet_Check/public/checklists" class="btn btn-outline-secondary btn-sm">Retour</a>
</div>

<form method="get" action="/Vet_Check/public/checklists/run" class="row g-2 mb-4">
    <input type="hidden" name="id" value="<?= (int) $checklist['id'] ?>">
    <div class="col-12 col-md-4">
        <label class="form-label" for="run_date">Date</label>
        <input type="date" id="run_date" name="date" class="form-control" value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="col-12 col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100">Changer la date</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="h6 mb-3">Tâches</h3>
        <?php if (empty($tasks)): ?>
            <div class="alert alert-info mb-0">Aucune tâche disponible dans cette checklist.</div>
        <?php endif; ?>

        <?php $lastSection = null; ?>
        <?php foreach ($tasks as $task): ?>
            <?php
            $section = $task['section_title'] ?? 'Sans section';
            $checked = $checksMap[(int) $task['id']] ?? null;
            ?>

            <?php if ($section !== $lastSection): ?>
                <h4 class="h6 mt-4 mb-2 text-primary-emphasis"><?= htmlspecialchars($section, ENT_QUOTES, 'UTF-8') ?></h4>
                <?php $lastSection = $section; ?>
            <?php endif; ?>

            <div class="border rounded-3 p-3 mb-2 d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="fw-semibold"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="small text-body-secondary">Ordre: <?= (int) $task['sort_order'] ?></div>
                    </div>
                    <?php if ($checked): ?>
                        <span class="badge text-bg-success">Cochée</span>
                    <?php else: ?>
                        <span class="badge text-bg-secondary">En attente</span>
                    <?php endif; ?>
                </div>

                <?php if ($checked): ?>
                    <div class="small text-body-secondary">
                        Cochée par <strong><?= htmlspecialchars($checked['user_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                        le <?= htmlspecialchars($checked['checked_at'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php else: ?>
                    <form method="post" action="/Vet_Check/public/checklists/check-task" class="d-flex gap-2">
                        <input type="hidden" name="instance_id" value="<?= (int) $instance['id'] ?>">
                        <input type="hidden" name="task_id" value="<?= (int) $task['id'] ?>">
                        <button class="btn btn-success btn-sm">Cocher</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
