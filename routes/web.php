<?php

declare(strict_types=1);

use App\Controllers\HomeController;

/** @var \Core\Router $router */
// $router->get('/', [HomeController::class, 'index']);
use App\Controllers\AuthController;
use App\Controllers\CategorieController;

$router->get('/connexion', [AuthController::class, 'showLogin']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);
$router->get('/inscription', [AuthController::class, 'showRegister']);
$router->post('/inscription', [AuthController::class, 'register']);

$router->get('/categories', [CategorieController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories', [CategorieController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/update', [CategorieController::class, 'update'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/delete', [CategorieController::class, 'delete'], ['auth', 'role:GERANT,ADMIN']);