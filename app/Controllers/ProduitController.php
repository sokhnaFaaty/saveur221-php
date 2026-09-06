<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CategorieService;
use App\Services\ProduitService;
use Core\View;
use Exceptions\AppException;

class ProduitController extends Controller
{
    public function __construct(
        private ProduitService $produitService,
    private CategorieService $categorieService,
        private \App\Services\UploadService $uploads,

    ) {}

    // Public : visiteur, client, ou staff - tout le monde peut consulter le catalogue
    public function index(): string
    {
        $terme = trim((string) $this->value('q', ''));
        $categorieId = $this->value('categorie');
    $categorieId = ($categorieId !== null && $categorieId !== '') ? (int) $categorieId : null;
    $dispoUniquement = $this->value('dispo') === 'disponibles';

    if ($terme !== '') {
        $produits = $this->produitService->rechercherProduit($terme);
    } elseif ($categorieId !== null) {
        $produits = $this->produitService->listerParCategorie($categorieId);
    } else {
        $produits = $this->produitService->listerProduits();
    }

    if ($dispoUniquement) {
        $produits = array_values(array_filter($produits, fn ($p) => $p->disponible()));
    }

    return View::render('produits/index', [
        'title'       => 'Notre carte & menus',
        'produits'    => $produits,
        'categories'  => $this->categorieService->listerCategories(),
        'categorieId' => $categorieId,
        'terme'       => $terme,
        'dispo'       => $this->value('dispo', 'tous'),
    ], 'layouts/public');
    }


    public function show(int $id): string
{
    try {
        $produit = $this->produitService->consulterProduit($id);
           $suggestions = array_filter(
            $this->produitService->listerProduitsDisponibles(),
            fn ($p) => $p->id !== $produit->id
        );
        return View::render('produits/show', [
            'title'       => $produit->libelle,
            'produit'     => $produit,
            'suggestions' => array_slice($suggestions, 0, 4),
        ], 'layouts/public');
    } catch (AppException $e) {
        http_response_code(404);
        return View::render('errors/404', ['title' => 'Produit introuvable'], 'layouts/public');
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