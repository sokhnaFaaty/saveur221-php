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
        $tous = $this->paiementService->listerTousLesPaiements();
        $pagination = paginer($tous, (int) $this->value('page', 1));
        return View::render('paiements/gestion', [
            'title' => 'Caisse & Reglements',
            'paiements' => $pagination['items'],
            'total' => array_sum(array_map(fn ($p) => $p->montant, $tous)),
            'totalTransactions' => count($tous),
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'vue' => $this->value('vue', 'tableau'),
        ], 'layouts/dashboard');
    }

    public function store(int $commandeId): never
    {
        try {
            $this->paiementService->enregistrerPaiement(
                $commandeId,
                $this->value('montant', ''),
                (string) $this->value('moyen', '')
            );
            flash('success', 'Paiement enregistre.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/commandes/' . $commandeId);
    }
}