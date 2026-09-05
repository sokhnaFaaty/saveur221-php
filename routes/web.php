<?php

declare(strict_types=1);

use App\Controllers\HomeController;

/** @var \Core\Router $router */
$router->get('/', [HomeController::class, 'index']);
use App\Controllers\AuthController;
use App\Controllers\CategorieController;
use App\Controllers\ProduitController;

$router->get('/connexion', [AuthController::class, 'showLogin']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);
$router->get('/inscription', [AuthController::class, 'showRegister']);
$router->post('/inscription', [AuthController::class, 'register']);

$router->get('/categories', [CategorieController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories', [CategorieController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/update', [CategorieController::class, 'update'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/delete', [CategorieController::class, 'delete'], ['auth', 'role:GERANT,ADMIN']);

->get('/produits', [ProduitController::class, 'index']);
$router->get('/produits/{id}', [ProduitController::class, 'show']);
$router->post('/produits', [ProduitController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/produits/{id}/delete', [ProduitController::class, 'delete'], ['auth', 'role:GERANT,ADMIN']);