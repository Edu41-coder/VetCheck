<?php
/** @var array $breadcrumbs */
$breadcrumbs = $breadcrumbs ?? [];
?>
<?php if (!empty($breadcrumbs)): ?>
<nav aria-label="Fil d'Ariane" class="mb-3">
    <ol class="breadcrumb app-breadcrumb mb-0">
        <?php $lastIndex = count($breadcrumbs) - 1; ?>
        <?php foreach ($breadcrumbs as $index => $item): ?>
            <?php
            $label = (string) ($item['label'] ?? '');
            $url = (string) ($item['url'] ?? '');
            $active = $index === $lastIndex;
            ?>
            <?php if ($active): ?>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></li>
            <?php else: ?>
                <li class="breadcrumb-item"><a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>
