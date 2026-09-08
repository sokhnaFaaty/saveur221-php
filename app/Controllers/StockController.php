<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CategorieService;
use App\Services\ProduitService;
use Core\View;
use Exceptions\AppException;

class StockController extends Controller
{
    public function __construct(
        private ProduitService $produitService,
        private CategorieService $categorieService,
    ) {}

    public function index(): string
    {
        $terme = trim((string) $this->value('q', ''));
        $categorie = $this->value('categorie');
        $categorieId = ($categorie !== null && $categorie !== '') ? (int) $categorie : null;

        if ($terme !== '') {
            $produits = $this->produitService->rechercherProduit($terme);
        } elseif ($categorieId !== null) {
            $produits = $this->produitService->listerParCategorie($categorieId);
        } else {
            $produits = $this->produitService->listerProduits();
        }

        if ($categorieId !== null && $terme !== '') {
            $produits = array_values(array_filter($produits, fn ($p) => $p->categorieId === $categorieId));
        }

        $pagination = paginer($produits, (int) $this->value('page', 1));
        return View::render('stocks/index', [
            'title' => 'Gestion & Reapprovisionnement des Stocks',
            'produits' => $pagination['items'],
            'categories' => $this->categorieService->listerCategories(),
            'categorieId' => $categorieId,
            'terme' => $terme,
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'vue' => $this->value('vue', 'cartes'),
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