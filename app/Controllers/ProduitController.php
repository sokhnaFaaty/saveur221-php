<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProduitService;
use Core\View;
use Exceptions\AppException;

class ProduitController extends Controller
{
    public function __construct(
        private ProduitService $produitService,
        private \App\Services\UploadService $uploads,
        ) {}

    // Public : visiteur, client, ou staff - tout le monde peut consulter le catalogue
    public function index(): string
    {
        $terme = trim((string) $this->value('q', ''));
        $produits = $terme === '' ? $this->produitService->listerProduits()
                                   : $this->produitService->rechercherProduit($terme);

        return View::render('produits/index', ['title' => 'Notre carte & menus', 'produits' => $produits], null);
    }

    public function show(int $id): string
{
    try {
        $produit = $this->produitService->consulterProduit($id);
        return View::render('produits/show', ['title' => $produit->libelle, 'produit' => $produit], null);
    } catch (AppException $e) {
        http_response_code(404);
        return View::render('errors/404', ['title' => 'Produit introuvable'], null);
    }
}

    // Prive : GERANT/ADMIN uniquement
    public function store(): never
    {
        try {
            $this->produitService->ajouterProduit([
                'libelle'           => $this->value('libelle'),
                'description'       => $this->value('description'),
                'prix'              => (float) $this->value('prix', 0),
                'quantite_stock'    => (int) $this->value('quantite_stock', 0),
                'categorie_id'      => (int) $this->value('categorie_id', 0),
                'seuil_alerte'      => (int) $this->value('seuil_alerte', 5),
                'temps_preparation' => (int) $this->value('temps_preparation', 0),
                'calories'          => (int) $this->value('calories', 0),
            ]);
            flash('success', 'Produit ajoute avec succes.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/produits');
    }

    public function delete(int $id): never
    {
        try {
            $this->produitService->supprimerProduit($id);
            flash('success', 'Produit supprime.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/produits');
    }
}