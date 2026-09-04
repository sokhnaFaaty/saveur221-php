<?php

declare(strict_types=1);

use App\Controllers\HomeController;

/** @var \Core\Router $router */
// $router->get('/', [HomeController::class, 'index']);
use App\Controllers\AuthController;

$router->get('/connexion', [AuthController::class, 'showLogin']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);