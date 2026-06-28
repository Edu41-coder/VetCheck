<?php
/** @var array|null $counter */
/** @var array $sections */
/** @var array $items */
/** @var string $action */
?>
<?php
$counter    = $counter ?? [];
$sections   = $sections ?? [];
$items      = $items ?? [];
$action     = $action ?? 'store';
$counterId  = $counter['id'] ?? null;
$createMode = $action === 'store';
?>

<div class="mb-4">
    <h2 class="h4 mb-1"><?= $createMode ? 'Créer un compteur' : 'Modifier le compteur' ?></h2>
    <p class="text-body-secondary mb-0">
        <?= $createMode
            ? 'Définissez les événements à comptabiliser.'
            : 'Gérez les informations et les événements de ce compteur.' ?>
    </p>
</div>

<form method="post" action="/Vet_Check/public/counters/<?= $action ?>" class="row g-3 mb-4">
    <?php if (!$createMode): ?>
        <input type="hidden" name="id" value="<?= $counterId ?>">
    <?php endif; ?>

    <div class="col-12 col-md-6">
        <label class="form-label" for="name">Nom</label>
        <input id="name" name="name" type="text" class="form-control"
               value="<?= htmlspecialchars($counter['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="slug">Slug</label>
        <input id="slug" name="slug" type="text" class="form-control"
               value="<?= htmlspecialchars($counter['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="event_label">Libellé de l'événement</label>
        <input id="event_label" name="event_label" type="text" class="form-control"
               placeholder="Ex : Lumières allumées"
               value="<?= htmlspecialchars($counter['event_label'] ?? 'Événement', ENT_QUOTES, 'UTF-8') ?>" required>
        <div class="form-text">Ce texte apparaît comme badge sur la page de comptage.</div>
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="2"><?= htmlspecialchars($counter['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <div class="col-12">
        <button class="btn btn-primary"><?= $createMode ? 'Créer' : 'Mettre à jour' ?></button>
        <a href="/Vet_Check/public/counters" class="btn btn-outline-secondary ms-2">Retour</a>
    </div>
</form>

<?php if (!$createMode): ?>
    <div class="row g-4">

        <!-- Sections -->
        <div class="col-12 col-lg-6">
            <div class="card p-3 shadow-sm">
                <h3 class="h6 mb-3">Sections</h3>
                <?php foreach ($sections as $section): ?>
                    <div class="mb-2 border-bottom pb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <span class="small text-body-secondary ms-2">ordre : <?= (int) $section['sort_order'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <form method="post" action="/Vet_Check/public/counters/section-store" class="row g-2 mt-2">
                    <input type="hidden" name="counter_id" value="<?= $counterId ?>">
                    <div class="col-12">
                        <label class="form-label" for="section_title">Nouvelle section</label>
                        <input id="section_title" name="title" type="text" class="form-control"
                               placeholder="Ex : Matin" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="section_order">Ordre</label>
                        <input id="section_order" name="sort_order" type="number" class="form-control" value="0">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-outline-primary">Ajouter section</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Événements (items) -->
        <div class="col-12 col-lg-6">
            <div class="card p-3 shadow-sm">
                <h3 class="h6 mb-3">Événements à compter</h3>
                <?php if (empty($items)): ?>
                    <div class="alert alert-info">Aucun événement pour ce compteur.</div>
                <?php endif; ?>
                <?php foreach ($items as $item): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <strong><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <div class="small text-body-secondary">
                                    Section : <?= htmlspecialchars($item['section_title'] ?? 'Sans section', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="/Vet_Check/public/counters/item-delete?id=<?= $item['id'] ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Supprimer cet événement ?');">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <form method="post" action="/Vet_Check/public/counters/item-store" class="row g-2 mt-2">
                    <input type="hidden" name="counter_id" value="<?= $counterId ?>">
                    <div class="col-12">
                        <label class="form-label" for="item_title">Titre de l'événement</label>
                        <input id="item_title" name="title" type="text" class="form-control"
                               placeholder="Ex : Lumière salle de chirurgie" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="item_section">Section</label>
                        <select id="item_section" name="section_id" class="form-select">
                            <option value="">Sans section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= $section['id'] ?>">
                                    <?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="item_order">Ordre</label>
                        <input id="item_order" name="sort_order" type="number" class="form-control" value="0">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Ajouter l'événement</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
<?php endif; ?>
