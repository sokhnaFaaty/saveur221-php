<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\PaiementRepositoryInterface;
use App\Interfaces\RecuRepositoryInterface;
use App\Services\CommandeService;
use App\Services\PdfService;
use Core\View;

class RecuController extends Controller
{
    public function __construct(
        private PaiementRepositoryInterface $paiements,
        private RecuRepositoryInterface $recus,
        private CommandeService $commandeService,
        private PdfService $pdf,
    ) {}

    public function show(int $paiementId): string
    {
        $paiement = $this->paiements->findById($paiementId);
        if ($paiement === null) {
            http_response_code(404);
            return View::render('errors/404', ['title' => 'Reçu introuvable'], 'layouts/public');
        }

        $recu = $this->recus->findByPaiement($paiementId);
        $commande = $this->commandeService->consulterCommande($paiement->commandeId);

        // Un recu n'existe que si le client a deja paye (recus cree uniquement a l'encaissement)
        // + securite : un client ne voit que le recu de ses propres commandes
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'CLIENT'
            && $commande->clientId !== (int) $_SESSION['user']['id']) {
            http_response_code(404);
            return View::render('errors/404', ['title' => 'Reçu introuvable'], 'layouts/public');
        }

        if ($recu === null) {
            http_response_code(404);
            return View::render('errors/404', ['title' => 'Reçu introuvable'], 'layouts/public');
        }

        return View::render('commandes/recu', [
            'title'     => $recu->numero,
            'recu'      => $recu,
            'paiement'  => $paiement,
            'commande'  => $commande,
        ], 'layouts/public');
    }

    public function pdf(int $paiementId): never
    {
        $paiement = $this->paiements->findById($paiementId);
        if ($paiement === null) {
            http_response_code(404);
            echo View::render('errors/404', ['title' => 'Reçu introuvable'], 'layouts/public');
            exit;
        }

        $recu = $this->recus->findByPaiement($paiementId);
        $commande = $this->commandeService->consulterCommande($paiement->commandeId);

        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'CLIENT'
            && $commande->clientId !== (int) $_SESSION['user']['id']) {
            http_response_code(404);
            echo View::render('errors/404', ['title' => 'Reçu introuvable'], 'layouts/public');
            exit;
        }

        if ($recu === null) {
            http_response_code(404);
            echo View::render('errors/404', ['title' => 'Reçu introuvable'], 'layouts/public');
            exit;
        }

        $this->pdf->generate('commandes/recu-pdf', [
            'recu'      => $recu,
            'paiement'  => $paiement,
            'commande'  => $commande,
        ], "recu-{$recu->numero}.pdf");
    }
}