<?php

declare(strict_types=1);

namespace App\Models;

class LigneCommande
{
    public function __construct(
        public readonly int $id,
        public readonly int $quantite,
        public readonly float $prixUnitaire,
        public readonly float $sousTotal,
        public readonly int $produitId,
        public readonly ?string $produitLibelle,
        public readonly int $commandeId,
        public readonly ?string $instructionsSpeciales,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            quantite: (int) $row->quantite,
            prixUnitaire: (float) $row->prix_unitaire,
            sousTotal: (float) $row->sous_total,
            produitId: (int) $row->produit_id,
            produitLibelle: $row->produit_libelle ?? null,
            commandeId: (int) $row->commande_id,
            instructionsSpeciales: $row->instructions_speciales,
        );
    }
}