<?php
/**
 * Partial pagination client-side
 * Expected variables:
 * - $paginationId
 */
$paginationId = $paginationId ?? 'datatable-pagination';
?>
<nav aria-label="Pagination historique" class="mt-3">
    <ul class="pagination pagination-sm mb-0" id="<?= htmlspecialchars($paginationId, ENT_QUOTES, 'UTF-8') ?>"></ul>
</nav>
