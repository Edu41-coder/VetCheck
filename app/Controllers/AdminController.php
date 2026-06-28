<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Config;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Security;
use App\Models\Role;
use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request): void
    {
        Security::requireRole(['admin']);

        $userModel = new User();
        $userCount = count($userModel->all());

        $counterModel = new \App\Models\Counter();
        $counterCount = count($counterModel->all());

        $this->render('admin/index', [
            'title' => 'Espace admin',
            'user' => Auth::user(),
            'userCount' => $userCount,
            'counterCount' => $counterCount,
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Espace admin', 'url' => '/Vet_Check/public/admin'],
            ],
        ]);
    }

    public function users(Request $request): void
    {
        Security::requireRole(['admin']);

        $model = new User();
        $users = $model->all();

        $this->render('admin/users/index', [
            'title' => 'Gestion des utilisateurs',
            'users' => $users,
            'user' => Auth::user(),
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Espace admin', 'url' => '/Vet_Check/public/admin'],
                ['label' => 'Utilisateurs', 'url' => '/Vet_Check/public/admin/users'],
            ],
        ]);
    }

    public function userCreate(Request $request): void
    {
        Security::requireRole(['admin']);

        $roleModel = new Role();
        $roles = $roleModel->allBusinessRoles();

        $this->render('admin/users/form', [
            'title' => 'Nouvel utilisateur',
            'editUser' => null,
            'roles' => $roles,
            'csrf_token' => Security::csrfToken(),
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Espace admin', 'url' => '/Vet_Check/public/admin'],
                ['label' => 'Utilisateurs', 'url' => '/Vet_Check/public/admin/users'],
                ['label' => 'Créer', 'url' => '/Vet_Check/public/admin/users/create'],
            ],
        ]);
    }

    public function userStore(Request $request): void
    {
        Security::requireRole(['admin']);

        $token = $request->input('_csrf');
        if (!Security::validateCsrf($token)) {
            Flash::set('error', 'Jeton CSRF invalide.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/create');
            exit;
        }

        $name    = trim((string) $request->input('name', ''));
        $email   = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');
        $roleId  = (int) $request->input('role_id', 0);
        $isAdmin = $request->input('is_admin') ? 1 : 0;

        if ($name === '' || $email === '' || $password === '' || $roleId === 0) {
            Flash::set('error', 'Tous les champs obligatoires doivent être remplis.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/create');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('error', 'Adresse email invalide.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/create');
            exit;
        }

        if (strlen($password) < 8) {
            Flash::set('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/create');
            exit;
        }

        $roleModel = new Role();
        if (!$roleModel->isBusinessRoleId($roleId)) {
            Flash::set('error', 'Le rôle sélectionné est invalide.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/create');
            exit;
        }

        $model = new User();
        if ($model->findByEmail($email)) {
            Flash::set('error', 'Cette adresse email est déjà utilisée.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/create');
            exit;
        }

        $model->create([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role_id'       => $roleId,
            'is_admin'      => $isAdmin,
        ]);

        Flash::set('success', 'Utilisateur créé avec succès.');
        header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
        exit;
    }

    public function userEdit(Request $request): void
    {
        Security::requireRole(['admin']);

        $id = (int) $request->input('id', 0);
        $model = new User();
        $editUser = $model->findById($id);

        if (!$editUser) {
            Flash::set('error', 'Utilisateur introuvable.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
            exit;
        }

        $currentUser = Auth::user();
        if ((int) $editUser['is_admin'] === 1 && (int) $editUser['id'] !== (int) ($currentUser['id'] ?? 0)) {
            Flash::set('error', 'Vous ne pouvez pas modifier le compte d\'un autre administrateur.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
            exit;
        }

        $roleModel = new Role();
        $roles = $roleModel->allBusinessRoles();

        $this->render('admin/users/form', [
            'title'      => 'Modifier l\'utilisateur',
            'editUser'   => $editUser,
            'roles'      => $roles,
            'csrf_token' => Security::csrfToken(),
            'breadcrumbs' => [
                ['label' => 'Tableau de bord', 'url' => '/Vet_Check/public/dashboard'],
                ['label' => 'Espace admin', 'url' => '/Vet_Check/public/admin'],
                ['label' => 'Utilisateurs', 'url' => '/Vet_Check/public/admin/users'],
                ['label' => 'Modifier', 'url' => '/Vet_Check/public/admin/users/edit?id=' . $id],
            ],
        ]);
    }

    public function userUpdate(Request $request): void
    {
        Security::requireRole(['admin']);

        $id    = (int) $request->input('id', 0);
        $token = $request->input('_csrf');

        if (!Security::validateCsrf($token)) {
            Flash::set('error', 'Jeton CSRF invalide.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/edit?id=' . $id);
            exit;
        }

        $name    = trim((string) $request->input('name', ''));
        $email   = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');
        $roleId  = (int) $request->input('role_id', 0);
        $isAdmin = $request->input('is_admin') ? 1 : 0;

        if ($name === '' || $email === '' || $roleId === 0) {
            Flash::set('error', 'Les champs nom, email et rôle sont obligatoires.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/edit?id=' . $id);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('error', 'Adresse email invalide.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/edit?id=' . $id);
            exit;
        }

        $roleModel = new Role();
        if (!$roleModel->isBusinessRoleId($roleId)) {
            Flash::set('error', 'Le rôle sélectionné est invalide.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/edit?id=' . $id);
            exit;
        }

        $model    = new User();
        $target   = $model->findById($id);
        if (!$target) {
            Flash::set('error', 'Utilisateur introuvable.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
            exit;
        }

        $currentUser = Auth::user();
        if ((int) $target['is_admin'] === 1 && (int) $target['id'] !== (int) ($currentUser['id'] ?? 0)) {
            Flash::set('error', 'Vous ne pouvez pas modifier le compte d\'un autre administrateur.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
            exit;
        }

        $existing = $model->findByEmail($email);
        if ($existing && (int) $existing['id'] !== $id) {
            Flash::set('error', 'Cette adresse email est déjà utilisée par un autre utilisateur.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users/edit?id=' . $id);
            exit;
        }

        $model->update($id, [
            'name'        => $name,
            'email'       => $email,
            'role_id'     => $roleId,
            'is_admin'    => $isAdmin,
        ]);

        if ($password !== '') {
            if (strlen($password) < 8) {
                Flash::set('error', 'Le mot de passe doit contenir au moins 8 caractères.');
                header('Location: ' . Config::get('app')['base_url'] . '/admin/users/edit?id=' . $id);
                exit;
            }
            $model->updatePassword($id, password_hash($password, PASSWORD_DEFAULT));
        }

        Flash::set('success', 'Utilisateur mis à jour.');
        header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
        exit;
    }

    public function userDelete(Request $request): void
    {
        Security::requireRole(['admin']);

        $id          = (int) $request->input('id', 0);
        $currentUser = Auth::user();

        if ((int) ($currentUser['id'] ?? 0) === $id) {
            Flash::set('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
            exit;
        }

        $model  = new User();
        $target = $model->findById($id);

        if (!$target) {
            Flash::set('error', 'Utilisateur introuvable.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
            exit;
        }

        if ((int) $target['is_admin'] === 1) {
            Flash::set('error', 'Vous ne pouvez pas supprimer un compte administrateur.');
            header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
            exit;
        }

        $model->delete($id);
        Flash::set('success', 'Utilisateur supprimé.');
        header('Location: ' . Config::get('app')['base_url'] . '/admin/users');
        exit;
    }
}
