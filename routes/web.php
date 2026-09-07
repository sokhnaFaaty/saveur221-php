<?php

declare(strict_types=1);

use App\Controllers\HomeController;

/** @var \Core\Router $router */
use App\Controllers\AuthController;
use App\Controllers\CategorieController;
use App\Controllers\ProduitController;
use App\Controllers\CommandeController;
use App\Controllers\PaiementController;
use App\Controllers\NotificationController;
use App\Controllers\AvisController;
use App\Controllers\DashboardController;
use App\Controllers\StockController;
use App\Controllers\ClientController;
use App\Controllers\StaffController;
use App\Controllers\ProfilController;
use App\Controllers\StatistiqueController;





$router->get('/', [HomeController::class, 'index']);

$router->get('/connexion', [AuthController::class, 'showLogin']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);
$router->get('/inscription', [AuthController::class, 'showRegister']);
$router->post('/inscription', [AuthController::class, 'register']);

$router->get('/dashboard', [DashboardController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);

$router->get('/categories', [CategorieController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories', [CategorieController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);
$router->get('/categories/creer', [CategorieController::class, 'create'], ['auth', 'role:GERANT,ADMIN']);
$router->get('/categories/{id}/modifier', [CategorieController::class, 'edit'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/update', [CategorieController::class, 'update'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/categories/{id}/delete', [CategorieController::class, 'delete'], ['auth', 'role:GERANT,ADMIN']);

$router->get('/produits', [ProduitController::class, 'index']);
$router->get('/produits/{id}', [ProduitController::class, 'show']);
$router->post('/produits', [ProduitController::class, 'store'], ['auth', 'role:GERANT,ADMIN']);
$router->get('/produits/creer', [ProduitController::class, 'create'], ['auth', 'role:GERANT,ADMIN']);
$router->get('/produits/{id}/modifier', [ProduitController::class, 'modifier'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/produits/{id}/update', [ProduitController::class, 'update'], ['auth', 'role:GERANT,ADMIN']);
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

$router->get('/notifications', [NotificationController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/notifications/{id}/lue', [NotificationController::class, 'markRead'], ['auth', 'role:GERANT,ADMIN']);

$router->post('/commandes/{commandeId}/avis', [AvisController::class, 'store'], ['auth', 'role:CLIENT']);
$router->get('/avis', [AvisController::class, 'index'], ['auth', 'role:ADMIN']);
$router->post('/avis/{id}/delete', [AvisController::class, 'delete'], ['auth', 'role:ADMIN']);

$router->get('/stocks', [StockController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/stocks/{id}/approvisionner', [StockController::class, 'approvisionner'], ['auth', 'role:GERANT,ADMIN']);


$router->get('/clients', [ClientController::class, 'index'], ['auth', 'role:ADMIN']);
$router->get('/staff', [StaffController::class, 'index'], ['auth', 'role:ADMIN']);
$router->post('/staff', [StaffController::class, 'store'], ['auth', 'role:ADMIN']);
$router->post('/staff/{id}/toggle', [StaffController::class, 'toggle'], ['auth', 'role:ADMIN']);
$router->post('/staff/{id}/delete', [StaffController::class, 'delete'], ['auth', 'role:ADMIN']);
$router->get('/profil', [ProfilController::class, 'index'], ['auth']);
$router->post('/profil', [ProfilController::class, 'update'], ['auth']);
$router->post('/profil/mot-de-passe', [ProfilController::class, 'updatePassword'], ['auth']);

$router->get('/statistiques', [StatistiqueController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);