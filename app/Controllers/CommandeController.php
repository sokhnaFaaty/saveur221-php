<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CommandeService;
use App\Services\PdfService;
use Core\View;
use Exceptions\AppException;

class CommandeController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
        private \App\Interfaces\FactureRepositoryInterface $factures,
        private \App\Interfaces\PaiementRepositoryInterface $paiements,
        private \App\Interfaces\AvisRepositoryInterface $avis,
        private \App\Services\PaiementService $paiementService,
        private \App\Interfaces\RecuRepositoryInterface $recus,
    ) {}

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
        $clientId = (int) $_SESSION['user']['id'];
        $commandes = $this->commandeService->listerMesCommandes($clientId);
        $paiementsParCommande = [];
        $avisParCommande = [];
        $mesAvis = [];
        foreach ($commandes as $commande) {
            $paiementsParCommande[$commande->id] = $this->paiements->findByCommande($commande->id);
            $avis = $this->avis->findByCommande($commande->id);
            $avisParCommande[$commande->id] = $avis;
            if ($avis !== null) {
                $mesAvis[] = ['avis' => $avis, 'numCommande' => $commande->numCommande, 'commande' => $commande];
            }
        }

        $paginationCommandes = paginer($commandes, (int) $this->value('page_commandes', 1), 6);
        $paginationAvis = paginer($mesAvis, (int) $this->value('page_avis', 1), 6);

        $commandesAvecPaiements = array_values(array_filter($commandes, fn($c) => ($paiementsParCommande[$c->id] ?? []) !== []));
        $paginationFactures = paginer($commandesAvecPaiements, (int) $this->value('page_factures', 1), 8);

        return View::render('commandes/mes-commandes', [
            'title' => 'Mes commandes',
            'commandes' => $paginationCommandes['items'],
            'pageCommandes' => $paginationCommandes['page'],
            'totalPagesCommandes' => $paginationCommandes['totalPages'],
            'totalCommandes' => $paginationCommandes['total'],
            'paiementsParCommande' => $paiementsParCommande,
            'avisParCommande' => $avisParCommande,
            'mesAvis' => $paginationAvis['items'],
            'pageAvis' => $paginationAvis['page'],
            'totalPagesAvis' => $paginationAvis['totalPages'],
            'totalAvis' => $paginationAvis['total'],
            'commandesFactures' => $paginationFactures['items'],
            'pageFactures' => $paginationFactures['page'],
            'totalPagesFactures' => $paginationFactures['totalPages'],
            'onglet' => $this->value('onglet', 'commandes'),
            'vue' => $this->value('vue', 'cartes'),
        ], 'layouts/public');
    }

    public function show(int $id): string
    {
        try {
            $commande = $this->commandeService->consulterCommande($id);
            return View::render('commandes/show', ['title' => $commande->numCommande, 'commande' => $commande], 'layouts/public');
        } catch (AppException $e) {
            http_response_code(404);
            return View::render('errors/404', ['title' => 'Commande introuvable'], null);
        }
    }

    // GERANT/ADMIN : toutes les commandes
    public function index(): string
    {
        $statutFiltre = $this->value('statut');
        $terme = trim((string) $this->value('q', ''));
        $paiementFiltre = (string) $this->value('paiement', '');

        if ($terme !== '') {
            $toutes = $this->commandeService->rechercherParNumero($terme);
            if ($statutFiltre) {
                $toutes = array_values(array_filter($toutes, fn ($c) => $c->statut === $statutFiltre));
            }
        } else {
            $toutes = $statutFiltre ? $this->commandeService->listerParStatut((string) $statutFiltre) : $this->commandeService->listerCommandes();
        }

        $paiementsParCommande = [];
        $statutPaiementParCommande = [];
        $resteParCommande = [];
        $recusParPaiement = [];
        foreach ($toutes as $commande) {
            $paiementsParCommande[$commande->id] = $this->paiements->findByCommande($commande->id);
            $resteParCommande[$commande->id] = $this->paiementService->montantRestant($commande->id);
            $statutPaiementParCommande[$commande->id] = $this->paiementService->calculerStatutPaiement($commande->id);
            foreach ($paiementsParCommande[$commande->id] as $paiement) {
                $recusParPaiement[$paiement->id] = $this->recus->findByPaiement($paiement->id);
            }
        }

        if ($paiementFiltre !== '') {
            $toutes = array_values(array_filter($toutes, fn ($c) => ($statutPaiementParCommande[$c->id] ?? null) === $paiementFiltre));
        }

        $pagination = paginer($toutes, (int) $this->value('page', 1));

        return View::render('commandes/gestion', [
            'title' => 'Gestion des Commandes Clients',
            'commandes' => $pagination['items'],
            'statutFiltre' => $statutFiltre,
            'terme' => $terme,
            'paiementFiltre' => $paiementFiltre,
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'vue' => $this->value('vue', 'cartes'),
            'paiementsParCommande' => $paiementsParCommande,
            'statutPaiementParCommande' => $statutPaiementParCommande,
            'resteParCommande' => $resteParCommande,
            'recusParPaiement' => $recusParPaiement,
        ], 'layouts/dashboard');
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

    public function facture(int $id): string
{
    $commande = $this->commandeService->consulterCommande($id);
    $facture = $this->factures->findByCommande($id);

    return View::render('commandes/facture', [
        'title'    => $facture?->numero ?? 'Facture',
        'commande' => $commande,
        'facture'  => $facture,
    ], 'layouts/public');
}
}