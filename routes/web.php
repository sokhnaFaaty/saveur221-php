<?php

declare(strict_types=1);

use App\Controllers\HomeController;

/** @var \Core\Router $router */
use App\Controllers\AuthController;
use App\Controllers\CategorieController;
use App\Controllers\ProduitController;
use App\Controllers\CommandeController;
use App\Controllers\PaiementController;


$router->get('/', [HomeController::class, 'index']);

$router->get('/connexion', [AuthController::class, 'showLogin']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);
$router->get('/inscription', [AuthController::class, 'showRegister']);
$router->post('/inscription', [AuthController::class, 'register']);

$router->get('/categories', [CategorieController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories', [CategorieController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/update', [CategorieController::class, 'update'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/delete', [CategorieController::class, 'delete'], ['auth', 'role:GERANT,ADMIN']);

$router->get('/produits', [ProduitController::class, 'index']);
$router->get('/produits/{id}', [ProduitController::class, 'show']);
$router->post('/produits', [ProduitController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/produits/{id}/delete', [ProduitController::class, 'delete'], ['auth', 'role:GERANT,ADMIN']);

$router->post('/commandes', [CommandeController::class, 'store'], ['auth', 'role:CLIENT']);
$router->get('/mes-commandes', [CommandeController::class, 'mesCommandes'], ['auth', 'role:CLIENT']);
$router->get('/commandes', [CommandeController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->get('/commandes/{id}', [CommandeController::class, 'show'], ['auth']);
$router->post('/commandes/{id}/statut', [CommandeController::class, 'changerStatut'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/commandes/{id}/annuler', [CommandeController::class, 'annuler'], ['auth']);

$router->get('/commandes/{id}/facture', [CommandeController::class, 'facture'], ['auth']);

$router->get('/paiements', [PaiementController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/commandes/{commandeId}/paiements', [PaiementController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);