http://localhost/Vet_Check/public/



# VetCheck

Application PHP MVC pour la gestion quotidienne de checklists en clinique veterinaire.

## Fonctionnalites implementees

- Authentification: login/logout avec sessions
- Roles: `veto`, `asv`, + flag `is_admin`
- CRUD checklists/taches/sections (admin)
- Execution quotidienne d'une checklist par date
- Cochage de taches avec tracabilite utilisateur
- Historique filtrable (utilisateur, checklist, dates)
- Tri/recherche/pagination front sur tableaux

## Structure

- `app/Core`: autoloader, router, security, auth, flash, db
- `app/Controllers`: `AuthController`, `HomeController`, `ChecklistController`
- `app/Models`: `User`, `Checklist`, `ChecklistTask`, `ChecklistSection`, `ChecklistInstance`, `TaskCheck`
- `app/Views`: layouts, partials, pages
- `public`: point d'entree + assets css/js
- `sql`: schema + seeds

## Routes principales

### Auth

- `GET /Vet_Check/public/login`
- `POST /Vet_Check/public/login`
- `GET /Vet_Check/public/logout`

### Dashboard / roles

- `GET /Vet_Check/public/dashboard` (auth)
- `GET /Vet_Check/public/admin` (admin)
- `GET /Vet_Check/public/veto` (role veto)
- `GET /Vet_Check/public/asv` (role asv)

### Checklists

- `GET /Vet_Check/public/checklists` (auth)
- `GET /Vet_Check/public/checklists/create` (admin)
- `POST /Vet_Check/public/checklists/store` (admin)
- `GET /Vet_Check/public/checklists/edit?id={id}` (admin)
- `POST /Vet_Check/public/checklists/update` (admin)
- `GET /Vet_Check/public/checklists/delete?id={id}` (admin)

### Sections / taches

- `POST /Vet_Check/public/checklists/section-store` (admin)
- `POST /Vet_Check/public/checklists/task-store` (admin)
- `POST /Vet_Check/public/checklists/task-update` (admin)
- `GET /Vet_Check/public/checklists/task-delete?id={id}` (admin)

### Execution et historique

- `GET /Vet_Check/public/checklists/run?id={checklist_id}&date=YYYY-MM-DD` (auth)
- `POST /Vet_Check/public/checklists/check-task` (auth)
- `GET /Vet_Check/public/checklists/history` (auth)

## Base de donnees

Importer:

- `sql/schema.sql`
- `sql/seeds/chenil_tasks.sql`
- `sql/seeds/users_seed.php` (script PHP)

## Lancer en local (XAMPP)

1. Placer le projet dans `htdocs/Vet_Check`
2. Importer le schema et les seeds
3. Ouvrir `http://localhost/Vet_Check/public`

## Comptes de test

Script: `php sql/seeds/users_seed.php`

- 2 vetos admins
- 1 veto non admin
- 4 ASV

Mot de passe seed par defaut: `password123`
