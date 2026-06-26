<?php
/** @var array|null $checklist */
/** @var array $sections */
/** @var array $tasks */
/** @var string $action */
?>
<?php
$checklist = $checklist ?? [];
$sections = $sections ?? [];
$tasks = $tasks ?? [];
$action = $action ?? 'store';
$checklistId = $checklist['id'] ?? null;
$createMode = $action === 'store';
?>
<div class="mb-4">
    <h2 class="h4 mb-1"><?= $createMode ? 'Créer une checklist' : 'Modifier la checklist' ?></h2>
    <p class="text-body-secondary mb-0"><?= $createMode ? 'Créez une nouvelle liste de tâches.' : 'Gérez les informations de cette checklist et ses tâches.' ?></p>
</div>

<form method="post" action="/Vet_Check/public/checklists/<?= $action ?>" class="row g-3 mb-4">
    <?php if (!$createMode): ?>
        <input type="hidden" name="id" value="<?= $checklistId ?>">
    <?php endif; ?>

    <div class="col-12 col-md-6">
        <label class="form-label" for="name">Nom</label>
        <input id="name" name="name" type="text" class="form-control" value="<?= htmlspecialchars($checklist['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="slug">Slug</label>
        <input id="slug" name="slug" type="text" class="form-control" value="<?= htmlspecialchars($checklist['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($checklist['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <div class="col-12">
        <button class="btn btn-primary"><?= $createMode ? 'Créer' : 'Mettre à jour' ?></button>
        <a href="/Vet_Check/public/checklists" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>
</form>

<?php if (!$createMode): ?>
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card p-3 shadow-sm">
                <h3 class="h6 mb-3">Sections</h3>
                <?php foreach ($sections as $section): ?>
                    <div class="mb-3 border-bottom pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <div class="small text-body-secondary">Ordre : <?= (int) $section['sort_order'] ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <form method="post" action="/Vet_Check/public/checklists/section-store" class="row g-3">
                    <input type="hidden" name="checklist_id" value="<?= $checklistId ?>">
                    <div class="col-12">
                        <label class="form-label" for="section_title">Nouvelle section</label>
                        <input id="section_title" name="title" type="text" class="form-control" placeholder="Ex: Avant de partir le midi" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="section_sort_order">Ordre</label>
                        <input id="section_sort_order" name="sort_order" type="number" class="form-control" value="0">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-outline-primary">Ajouter section</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card p-3 shadow-sm">
                <h3 class="h6 mb-3">Tâches</h3>
                <?php if (empty($tasks)): ?>
                    <div class="alert alert-info">Aucune tâche pour cette checklist.</div>
                <?php endif; ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <strong><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <div class="small text-body-secondary">
                                    Section : <?= htmlspecialchars($task['section_title'] ?? 'Sans section', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="/Vet_Check/public/checklists/edit?id=<?= $checklistId ?>&task_edit=<?= $task['id'] ?>" class="btn btn-outline-secondary btn-sm">Modifier</a>
                                <a href="/Vet_Check/public/checklists/task-delete?id=<?= $task['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cette tâche ?');">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <form method="post" action="/Vet_Check/public/checklists/task-store" class="row g-3">
                    <input type="hidden" name="checklist_id" value="<?= $checklistId ?>">
                    <div class="col-12">
                        <label class="form-label" for="task_title">Titre de la tâche</label>
                        <input id="task_title" name="title" type="text" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="task_section">Section</label>
                        <select id="task_section" name="section_id" class="form-select">
                            <option value="">Sans section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= $section['id'] ?>"><?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="task_order">Ordre</label>
                        <input id="task_order" name="sort_order" type="number" class="form-control" value="0">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Ajouter la tâche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
