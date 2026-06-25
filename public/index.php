<?php

declare(strict_types=1);

use App\Core\Autoloader;
use App\Core\Config;
use App\Core\Request;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;

require_once __DIR__ . '/../app/Core/Autoloader.php';
Autoloader::register();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

Config::load('app', __DIR__ . '/../config/app.php');
Config::load('database', __DIR__ . '/../config/database.php');

date_default_timezone_set(Config::get('app')['timezone'] ?? 'UTC');

$router = new Router();
$request = new Request();

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin'], ['guest']);
$router->post('/login', [AuthController::class, 'login'], ['guest']);
$router->get('/logout', [AuthController::class, 'logout'], ['auth']);
$router->get('/dashboard', [HomeController::class, 'dashboard'], ['auth']);
$router->get('/admin', [HomeController::class, 'adminPanel'], ['role:admin']);
$router->get('/veto', [HomeController::class, 'vetoArea'], ['role:veto']);
$router->get('/asv', [HomeController::class, 'asvArea'], ['role:asv']);
$router->get('/checklists', [\App\Controllers\ChecklistController::class, 'index'], ['auth']);
$router->get('/checklists/run', [\App\Controllers\ChecklistController::class, 'run'], ['auth']);
$router->post('/checklists/check-task', [\App\Controllers\ChecklistController::class, 'checkTask'], ['auth']);
$router->get('/checklists/history', [\App\Controllers\ChecklistController::class, 'history'], ['auth']);
$router->get('/checklists/create', [\App\Controllers\ChecklistController::class, 'create'], ['role:admin']);
$router->post('/checklists/store', [\App\Controllers\ChecklistController::class, 'store'], ['role:admin']);
$router->get('/checklists/edit', [\App\Controllers\ChecklistController::class, 'edit'], ['role:admin']);
$router->post('/checklists/update', [\App\Controllers\ChecklistController::class, 'update'], ['role:admin']);
$router->get('/checklists/delete', [\App\Controllers\ChecklistController::class, 'delete'], ['role:admin']);
$router->post('/checklists/task-store', [\App\Controllers\ChecklistController::class, 'taskStore'], ['role:admin']);
$router->post('/checklists/task-update', [\App\Controllers\ChecklistController::class, 'taskUpdate'], ['role:admin']);
$router->get('/checklists/task-delete', [\App\Controllers\ChecklistController::class, 'taskDelete'], ['role:admin']);
$router->post('/checklists/section-store', [\App\Controllers\ChecklistController::class, 'sectionStore'], ['role:admin']);

$router->dispatch($request);
