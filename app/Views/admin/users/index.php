<?php
/** @var array $users */
/** @var array|null $user */
?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="h4 mb-1">Utilisateurs</h2>
        <p class="text-body-secondary mb-0">Gestion des comptes de la clinique.</p>
    </div>
    <a href="/Vet_Check/public/admin/users/create" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1" aria-hidden="true">
            <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
        </svg>
        Nouvel utilisateur
    </a>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-md-8">
        <label class="form-label" for="users-search">Recherche</label>
        <input id="users-search" type="text" class="form-control" placeholder="Nom, email, rôle…">
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label" for="users-page-size">Lignes par page</label>
        <select id="users-page-size" class="form-select">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table id="users-table" class="table table-striped align-middle">
        <thead>
            <tr>
                <th data-sort-index="0">Nom</th>
                <th data-sort-index="1">Email</th>
                <th data-sort-index="2">Rôle</th>
                <th data-sort-index="3">Privilège admin</th>
                <th data-sort-index="4">Inscrit le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr data-empty="1">
                    <td colspan="6" class="text-center text-body-secondary">Aucun utilisateur enregistré.</td>
                </tr>
            <?php else: ?>
                <tr data-empty="1" style="display:none;">
                    <td colspan="6" class="text-center text-body-secondary">Aucun résultat.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($users as $u): ?>
                <?php
                $roleBadge = match ($u['role_name'] ?? '') {
                    'veto'  => 'bg-primary',
                    'asv'   => 'bg-info text-dark',
                    default => 'bg-secondary',
                };
                $isSelf       = ((int) ($user['id'] ?? 0)) === (int) $u['id'];
                $isOtherAdmin = !$isSelf && (int) $u['is_admin'] === 1;
                ?>
                <tr data-row="1">
                    <td>
                        <strong><?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php if ($isSelf): ?>
                            <span class="badge bg-secondary ms-1 small">Vous</span>
                        <?php endif; ?>
                    </td>
                    <td class="small"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span class="badge <?= $roleBadge ?>">
                            <?= htmlspecialchars($u['role_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td>
                        <?php if ((int) $u['is_admin'] === 1): ?>
                            <span class="badge bg-success">Oui</span>
                        <?php else: ?>
                            <span class="text-body-secondary small">Non</span>
                        <?php endif; ?>
                    </td>
                    <td class="small">
                        <?= htmlspecialchars(
                            isset($u['created_at'])
                                ? (new DateTime($u['created_at']))->format('d/m/Y')
                                : '—',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <?php if (!$isOtherAdmin): ?>
                                <a href="/Vet_Check/public/admin/users/edit?id=<?= (int) $u['id'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    Modifier
                                </a>
                            <?php endif; ?>
                            <?php if (!$isSelf && !$isOtherAdmin): ?>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUserModal"
                                        data-user-id="<?= (int) $u['id'] ?>"
                                        data-user-name="<?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?>">
                                    Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="users-pagination" class="mt-3"></div>

<!-- Modal confirmation suppression -->
<div class="modal fade app-modal" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Supprimer l'utilisateur <strong id="deleteUserName"></strong> ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <a id="deleteUserConfirmBtn" href="#" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('deleteUserModal');
        if (!modal) return;

        modal.addEventListener('show.bs.modal', function (e) {
            var btn      = e.relatedTarget;
            var userId   = btn.getAttribute('data-user-id');
            var userName = btn.getAttribute('data-user-name');

            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserConfirmBtn').href =
                '/Vet_Check/public/admin/users/delete?id=' + userId;
        });
    })();
</script>

<script>
    (function () {
        var tableId      = 'users-table';
        var searchId     = 'users-search';
        var pageSizeId   = 'users-page-size';
        var paginationId = 'users-pagination';

        if (window.DataTable) {
            new window.DataTable(tableId, searchId, pageSizeId, paginationId);
        }
    })();
</script>
