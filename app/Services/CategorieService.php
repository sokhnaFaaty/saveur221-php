<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CategorieRepositoryInterface;
use App\Models\Categorie;
use Exceptions\CategorieInexistanteException;
use Exceptions\ValidationException;

class CategorieService
{
    public function __construct(
        private CategorieRepositoryInterface $categories,
        private UploadService $uploads,
    ) {}

    public function ajouterCategorie(string $libelle, ?string $description, ?string $image = null): Categorie
    {
        if (!Validator::estRempli($libelle)) {
            throw new ValidationException('Le libelle de la categorie est obligatoire.', 'libelle');
        }
        $this->verifierUnicite($libelle, null);
        return $this->categories->create([
            'libelle' => trim($libelle),
            'description' => $description,
            'image' => $image,
        ]);
    }

    public function listerCategories(): array
    {
        return $this->categories->findAll();
    }

    public function rechercherCategorie(string $motCle): array
    {
        return $this->categories->search($motCle);
    }

    public function modifierCategorie(int $id, string $libelle, ?string $description, ?string $image = null): void
    {
        if ($this->categories->findById($id) === null) {
            throw new CategorieInexistanteException("Aucune categorie trouvee avec l'id $id");
        }
        if (!Validator::estRempli($libelle)) {
            throw new ValidationException('Le libelle de la categorie est obligatoire.', 'libelle');
        }
        $this->verifierUnicite($libelle, $id);
        $this->categories->update($id, [
            'libelle' => trim($libelle),
            'description' => $description,
            'image' => $image,
        ]);
    }

    private function verifierUnicite(string $libelle, ?int $idExclu): void
    {
        $libelle = trim($libelle);
        $normalise = mb_strtolower(str_replace(' ', '', $libelle));
        foreach ($this->categories->findAll() as $existant) {
            if ($idExclu !== null && $existant->id === $idExclu) {
                continue;
            }
            $existantNormalise = mb_strtolower(str_replace(' ', '', (string) $existant->libelle));
            if ($existantNormalise === $normalise) {
                throw new ValidationException("Une categorie porte deja le libelle \"$libelle\".", 'libelle');
            }
        }
    }

    public function supprimerCategorie(int $id): void
    {
        // La verification "contient des produits ?" est deja faite dans le Repository.
        $this->categories->delete($id);
    }

    public function listerCategoriesSupprimees(): array
    {
        return $this->categories->findDeleted();
    }

    public function restaurerCategorie(int $id): void
    {
        $this->categories->restore($id);
    }

    public function supprimerCategorieDefinitivement(int $id): void
    {
        $this->categories->forceDelete($id);
    }
}