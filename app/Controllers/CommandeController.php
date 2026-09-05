<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CommandeService;
use Core\View;
use Exceptions\AppException;

class CommandeController extends Controller
{
    public function __construct(private CommandeService $commandeService) {}

    // Client : passe une commande a partir du panier (JSON envoye par le JS)
    public function store(): never
    {
        try {
            $panier = $this->input()['lignes'] ?? [];
            $commande = $this->commandeService->passerCommande((int) ($_SESSION['user']['id']), $panier);
            flash('success', "Commande {$commande->numCommande} enregistree.");
            View::redirect('/mes-commandes');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
            View::redirect('/produits');
        }
    }

    // Client : historique de ses commandes
    public function mesCommandes(): string
    {
        $commandes = $this->commandeService->listerMesCommandes((int) $_SESSION['user']['id']);
        return View::render('commandes/mes-commandes', ['title' => 'Mes commandes', 'commandes' => $commandes], null);
    }

    public function show(int $id): string
    {
        try {
            $commande = $this->commandeService->consulterCommande($id);
            return View::render('commandes/show', ['title' => $commande->numCommande, 'commande' => $commande], null);
        } catch (AppException $e) {
            http_response_code(404);
            return View::render('errors/404', ['title' => 'Commande introuvable'], null);
        }
    }

    // GERANT/ADMIN : toutes les commandes
    public function index(): string
    {
        $commandes = $this->commandeService->listerCommandes();
        return View::render('commandes/index', ['title' => 'Gestion des commandes', 'commandes' => $commandes], null);
    }

    public function changerStatut(int $id): never
    {
        try {
            $this->commandeService->changerStatut($id, (string) $this->value('statut', ''));
            flash('success', 'Statut mis a jour.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/commandes');
    }

    public function annuler(int $id): never
    {
        try {
            $this->commandeService->annulerCommande($id);
            flash('success', 'Commande annulee, stock restaure.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/commandes');
    }
}