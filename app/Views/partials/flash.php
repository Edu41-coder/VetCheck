<?php if (\App\Core\Flash::has()): ?>
    <?php foreach (\App\Core\Flash::get() as $type => $messages): ?>
        <?php
        $alertClass = match ($type) {
            'success' => 'alert alert-success alert-dismissible fade show',
            'error' => 'alert alert-danger alert-dismissible fade show',
            'warning' => 'alert alert-warning alert-dismissible fade show',
            'info' => 'alert alert-warning bg-warning-subtle border-warning-subtle text-warning-emphasis alert-dismissible fade show',
            default => 'alert alert-secondary alert-dismissible fade show',
        };
        ?>
        <?php foreach ($messages as $message): ?>
            <div class="<?= $alertClass ?> app-flash" role="alert" data-auto-dismiss="5000">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <script>
        window.addEventListener('load', function () {
            document.querySelectorAll('.app-flash[data-auto-dismiss]').forEach(function (flashEl) {
                var delay = Number(flashEl.getAttribute('data-auto-dismiss')) || 5000;

                window.setTimeout(function () {
                    if (window.bootstrap && window.bootstrap.Alert) {
                        window.bootstrap.Alert.getOrCreateInstance(flashEl).close();
                        return;
                    }

                    flashEl.remove();
                }, delay);
            });
        });
    </script>
<?php endif; ?>
