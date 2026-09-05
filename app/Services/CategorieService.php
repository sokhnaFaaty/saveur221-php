<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CategorieRepositoryInterface;
use App\Models\Categorie;
use Exceptions\CategorieInexistanteException;
use Exceptions\ValidationException;

class CategorieService
{
    public function __construct(private CategorieRepositoryInterface $categories) {}

    public function ajouterCategorie(string $libelle, ?string $description): Categorie
    {
        if (!Validator::estRempli($libelle)) {
            throw new ValidationException('Le libelle de la categorie est obligatoire.');
        }
        return $this->categories->create(['libelle' => trim($libelle), 'description' => $description]);
    }

    public function listerCategories(): array
    {
        return $this->categories->findAll();
    }

    public function rechercherCategorie(string $motCle): array
    {
        return $this->categories->search($motCle);
    }

    public function modifierCategorie(int $id, string $libelle, ?string $description): void
    {
        if ($this->categories->findById($id) === null) {
            throw new CategorieInexistanteException("Aucune categorie trouvee avec l'id $id");
        }
        if (!Validator::estRempli($libelle)) {
            throw new ValidationException('Le libelle de la categorie est obligatoire.');
        }
        $this->categories->update($id, ['libelle' => trim($libelle), 'description' => $description]);
    }

    public function supprimerCategorie(int $id): void
    {
        // La verification "contient des produits ?" est deja faite dans le Repository.
        $this->categories->delete($id);
    }
}