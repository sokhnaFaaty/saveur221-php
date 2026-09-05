<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\PaiementService;
use Core\View;
use Exceptions\AppException;

class PaiementController extends Controller
{
    public function __construct(private PaiementService $paiementService) {}

    // GERANT/ADMIN : la caisse
    public function index(): string
    {
        return View::render('paiements/index', [
            'title'     => 'Caisse & reglements',
            'paiements' => $this->paiementService->listerTousLesPaiements(),
        ], null);
    }

    public function store(int $commandeId): never
    {
        try {
            $this->paiementService->enregistrerPaiement(
                $commandeId,
                (float) $this->value('montant', 0),
                (string) $this->value('moyen', 'ESPECES')
            );
            flash('success', 'Paiement enregistre.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/commandes/' . $commandeId);
    }
}