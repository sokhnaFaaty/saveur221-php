<?php

declare(strict_types=1);

namespace App\Models;

class Facture
{
    public function __construct(
        public readonly int $id,
        public readonly string $numero,
        public readonly string $dateEmission,
        public readonly float $montantTotal,
        public readonly int $commandeId,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            numero: $row->numero,
            dateEmission: $row->date_emission,
            montantTotal: (float) $row->montant_total,
            commandeId: (int) $row->commande_id,
        );
    }
}