<?php
/** @var array|null $editUser */
/** @var array $roles */
/** @var string $csrf_token */
$isEdit  = $editUser !== null;
$action  = $isEdit
    ? '/Vet_Check/public/admin/users/update'
    : '/Vet_Check/public/admin/users/store';
?>
<div class="mb-4">
    <h2 class="h4 mb-1"><?= $isEdit ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' ?></h2>
    <p class="text-body-secondary mb-0">
        <?= $isEdit
            ? 'Modifiez les informations du compte. Laissez le mot de passe vide pour le conserver.'
            : 'Remplissez le formulaire pour créer un nouveau compte.' ?>
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" novalidate>
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int) $editUser['id'] ?>">
            <?php endif; ?>

            <!-- Nom -->
            <div class="mb-3">
                <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-control"
                       value="<?= htmlspecialchars($editUser['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       required
                       maxlength="150"
                       autocomplete="name">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       value="<?= htmlspecialchars($editUser['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       required
                       maxlength="255"
                       autocomplete="email">
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">
                    Mot de passe
                    <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?>
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       <?= $isEdit ? '' : 'required' ?>
                       minlength="8"
                       autocomplete="new-password"
                       placeholder="<?= $isEdit ? 'Laisser vide pour ne pas modifier' : 'Minimum 8 caractères' ?>">
                <?php if ($isEdit): ?>
                    <div class="form-text">Renseignez ce champ uniquement si vous souhaitez changer le mot de passe.</div>
                <?php endif; ?>
            </div>

            <!-- Rôle -->
            <div class="mb-3">
                <label for="role_id" class="form-label">Rôle <span class="text-danger">*</span></label>
                <select id="role_id" name="role_id" class="form-select" required>
                    <option value="">-- Choisir un rôle --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= (int) $role['id'] ?>"
                            <?= (int) ($editUser['role_id'] ?? 0) === (int) $role['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Privilège admin -->
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox"
                           id="is_admin"
                           name="is_admin"
                           value="1"
                           class="form-check-input"
                           <?= (int) ($editUser['is_admin'] ?? 0) === 1 ? 'checked' : '' ?>>
                    <label for="is_admin" class="form-check-label">Accès administrateur</label>
                    <div class="form-text">Ce privilège donne l'accès à la gestion des utilisateurs et des checklists.</div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l\'utilisateur' ?>
                </button>
                <a href="/Vet_Check/public/admin/users" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
