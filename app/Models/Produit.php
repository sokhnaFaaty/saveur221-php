<?php

declare(strict_types=1);

namespace App\Models;

class Produit
{
    public function __construct(
        public readonly int $id,
        public readonly string $libelle,
        public readonly ?string $description,
        public readonly float $prix,
        public readonly int $quantiteStock,
        public readonly int $categorieId,
        public readonly ?string $categorieLibelle,
        public readonly ?string $image,
        public readonly int $seuilAlerte,
        public readonly ?int $tempsPreparation,
        public readonly ?int $calories,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            libelle: $row->libelle,
            description: $row->description,
            prix: (float) $row->prix,
            quantiteStock: (int) $row->quantite_stock,
            categorieId: (int) $row->categorie_id,
            categorieLibelle: $row->categorie_libelle ?? null,
            image: $row->image,
            seuilAlerte: (int) $row->seuil_alerte,
            tempsPreparation: $row->temps_preparation !== null ? (int) $row->temps_preparation : null,
            calories: $row->calories !== null ? (int) $row->calories : null,
        );
    }

    // Equivalent Etat.DISPONIBLE / NON_DISPONIBLE du Java : jamais stocke en base,
    // toujours recalcule depuis quantite_stock.
    public function disponible(): bool
    {
        return $this->quantiteStock > 0;
    }

    public function stockFaible(): bool
    {
        return $this->quantiteStock > 0 && $this->quantiteStock <= $this->seuilAlerte;
    }

    public function estEnRupture(): bool
    {
        return $this->quantiteStock <= 0;
    }
}
