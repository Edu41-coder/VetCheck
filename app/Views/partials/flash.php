<?php if (\App\Core\Flash::has()): ?>
    <?php foreach (\App\Core\Flash::get() as $type => $messages): ?>
        <?php
        $alertClass = match ($type) {
            'success' => 'alert alert-success',
            'error' => 'alert alert-danger',
            'warning' => 'alert alert-warning',
            'info' => 'alert alert-info',
            default => 'alert alert-secondary',
        };
        ?>
        <?php foreach ($messages as $message): ?>
            <div class="<?= $alertClass ?> app-flash" role="alert">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>
