<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProduitService;
use Core\View;
use Exceptions\AppException;

class StockController extends Controller
{
    public function __construct(private ProduitService $produitService) {}

    public function index(): string
    {
        return View::render('stocks/index', [
            'title' => 'Gestion & Reapprovisionnement des Stocks',
            'produits' => $this->produitService->listerProduits(),
        ], 'layouts/dashboard');
    }

    public function approvisionner(int $id): never
    {
        try {
            $this->produitService->approvisionner($id, $this->value('quantite', ''));
            flash('success', 'Stock mis a jour.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/stocks');
    }
}