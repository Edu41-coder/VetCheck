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
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

$router->dispatch($request);
