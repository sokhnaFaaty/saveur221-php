<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CategorieRepositoryInterface;
use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Produit;
use Exceptions\CategorieInexistanteException;
use Exceptions\ProduitInexistantException;
use Exceptions\ValidationException;

class ProduitService
{
    public function __construct(
        private ProduitRepositoryInterface $produits,
        private CategorieRepositoryInterface $categories,
    ) {}

    public function ajouterProduit(array $data): Produit
    {
        $this->validerDonnees($data);
        return $this->produits->create($data);
    }

    public function listerProduits(): array
    {
        return $this->produits->findAll();
    }

    public function listerParCategorie(int $categorieId): array
    {
        return $this->produits->findByCategorie($categorieId);
    }

    public function rechercherProduit(string $motCle): array
    {
        return $this->produits->search($motCle);
    }

    public function listerProduitsDisponibles(): array
    {
        return array_values(array_filter($this->produits->findAll(), fn (Produit $p) => $p->disponible()));
    }

    public function listerProduitsEnRupture(): array
    {
        return $this->produits->findEnRupture();
    }

    public function listerProduitsStockFaible(): array
    {
        return $this->produits->findStockFaible();
    }

    public function modifierProduit(int $id, array $data): void
    {
        if ($this->produits->findById($id) === null) {
            throw new ProduitInexistantException("Aucun produit trouve avec l'id $id");
        }
        $this->validerDonnees($data);
        $this->produits->update($id, $data);
    }

    public function supprimerProduit(int $id): void
    {
        $this->produits->delete($id);
    }

    public function approvisionner(int $id, int $quantite): void
    {
        if ($quantite <= 0) {
            throw new ValidationException('La quantite doit etre positive.');
        }
        $this->produits->restaurerStock($id, $quantite);
    }

    private function validerDonnees(array $data): void
    {
        if (!Validator::estRempli($data['libelle'] ?? null)) {
            throw new ValidationException('Le libelle du produit est obligatoire.');
        }
        if (($data['prix'] ?? -1) < 0) {
            throw new ValidationException('Le prix ne peut pas etre negatif.');
        }
        if (($data['quantite_stock'] ?? -1) < 0) {
            throw new ValidationException('La quantite en stock ne peut pas etre negative.');
        }
        if ($this->categories->findById((int) ($data['categorie_id'] ?? 0)) === null) {
            throw new CategorieInexistanteException('Categorie invalide.');
        }
    }
}