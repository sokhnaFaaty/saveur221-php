<?php

declare(strict_types=1);

namespace App\Models;

class Recu
{
    public function __construct(
        public readonly int $id,
        public readonly string $numero,
        public readonly string $dateEmission,
        public readonly float $montant,
        public readonly int $paiementId,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            numero: $row->numero,
            dateEmission: $row->date_emission,
            montant: (float) $row->montant,
            paiementId: (int) $row->paiement_id,
        );
    }
}