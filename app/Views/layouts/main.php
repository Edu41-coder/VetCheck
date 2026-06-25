<?php
/** @var string $viewFile */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1877f2">
    <title><?= htmlspecialchars($title ?? 'VetCheck', ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Vet_Check/public/assets/css/app.css">
</head>
<body class="bg-app text-dark">
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="container py-3 py-md-4">
        <?php require __DIR__ . '/../partials/flash.php'; ?>
        <div class="app-surface rounded-4 shadow-sm p-3 p-md-4">
            <?php require __DIR__ . '/../partials/breadcrumb.php'; ?>
            <?php require $viewFile; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Vet_Check/public/assets/js/datatable-pagination.js"></script>
    <script src="/Vet_Check/public/assets/js/datatable.js"></script>
</body>
</html>
