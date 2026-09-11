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

    // Prive : GERANT/ADMIN uniquement (route /produits protegee par le role)
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
        return View::render('produits/gestion', [
            'title' => 'Gestion des Plats & Menus',
            'produits' => $pagination['items'],
            'categoriesProduits' => $this->categorieService->listerCategories(),
            'categorieId' => $categorieId,
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'vue' => $this->value('vue', 'tableau'),
        ], 'layouts/dashboard');
    }

    // Public : toujours la vue catalogue, quel que soit le role connecte
    public function indexPublic(): string
    {
        return $this->renderCataloguePublic();
    }

    private function renderCataloguePublic(): string
    {
        $terme = trim((string) $this->value('q', ''));
        $categorieId = $this->value('categorie');
        $categorieId = ($categorieId !== null && $categorieId !== '') ? (int) $categorieId : null;
        $produits = $terme !== '' ? $this->produitService->rechercherProduit($terme)
            : ($categorieId !== null ? $this->produitService->listerParCategorie($categorieId) : $this->produitService->listerProduits());

        return View::render('produits/index', [
            'title' => 'Notre carte & menus', 'produits' => $produits,
            'categories' => $this->categorieService->listerCategories(), 'categorieId' => $categorieId, 'terme' => $terme,
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
            $imageUrl = null;
            $fichier = $_FILES['image_file'] ?? [];
            $lien = trim((string) $this->value('image_url', ''));
            if (($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $imageUrl = $this->uploads->upload($fichier);
            } elseif ($lien !== '') {
                $imageUrl = $lien;
            }

            $this->produitService->ajouterProduit([
                'libelle'           => $this->value('libelle'),
                'description'       => $this->value('description'),
                'prix'              => $this->valeurNumerique('prix', 0),
                'quantite_stock'    => $this->valeurNumerique('quantite_stock', 0),
                'categorie_id'      => $this->valeurNumerique('categorie_id', 0),
                'seuil_alerte'      => $this->valeurNumerique('seuil_alerte', 5),
                'temps_preparation' => $this->valeurNumerique('temps_preparation', 0),
                'calories'          => $this->valeurNumerique('calories', 0),
                'image'             => $imageUrl,
            ]);
            flash('success', 'Produit ajoute avec succes.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        
        View::redirect('/produits');
    }
    public function create(): string
{
    return View::render('produits/form', ['title' => 'Nouveau plat', 'produit' => null, 'categories' => $this->categorieService->listerCategories()], 'layouts/dashboard');
}

    public function modifier(int $id): string
    {
        try {
            $produit = $this->produitService->consulterProduit($id);
            return View::render('produits/form', [
                'title' => 'Modifier un plat',
                'produit' => $produit,
                'categories' => $this->categorieService->listerCategories(),
            ], 'layouts/dashboard');
        } catch (AppException $e) {
            http_response_code(404);
            return View::render('errors/404', ['title' => 'Produit introuvable'], 'layouts/dashboard');
        }
    }

        public function update(int $id): never
    {
        try {
            $imageUrl = null;
            $fichier = $_FILES['image_file'] ?? [];
            $lien = trim((string) $this->value('image_url', ''));
            if (($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $imageUrl = $this->uploads->upload($fichier);
            } elseif ($lien !== '') {
                $imageUrl = $lien;
            }

            $data = [
                'libelle'           => $this->value('libelle'),
                'description'       => $this->value('description'),
                'prix'              => $this->valeurNumerique('prix', 0),
                'quantite_stock'    => $this->valeurNumerique('quantite_stock', 0),
                'categorie_id'      => $this->valeurNumerique('categorie_id', 0),
                'seuil_alerte'      => $this->valeurNumerique('seuil_alerte', 5),
                'temps_preparation' => $this->valeurNumerique('temps_preparation', 0),
                'calories'          => $this->valeurNumerique('calories', 0),
            ];
            if ($imageUrl !== null) {
                $data['image'] = $imageUrl; // remplace seulement si une image a ete fournie
            }
            $this->produitService->modifierProduit($id, $data);
            flash('success', 'Produit modifie avec succes.');
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

    public function corbeille(): string
    {
        $pagination = paginer($this->produitService->listerProduitsSupprimes(), (int) $this->value('page', 1));
        return View::render('produits/corbeille', [
            'title' => 'Corbeille des plats',
            'produits' => $pagination['items'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ], 'layouts/dashboard');
    }

    public function restaurer(int $id): never
    {
        try {
            $this->produitService->restaurerProduit($id);
            flash('success', 'Produit restaure.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/produits/corbeille');
    }

    public function supprimerDefinitivement(int $id): never
    {
        try {
            $this->produitService->supprimerProduitDefinitivement($id);
            flash('success', 'Produit supprime definitivement.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/produits/corbeille');
    }
}