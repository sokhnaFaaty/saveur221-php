<?php

declare(strict_types=1);

namespace App\Models;

class Paiement
{
    public const WAVE = 'WAVE';
    public const ORANGE_MONEY = 'ORANGE_MONEY';
    public const ESPECES = 'ESPECES';

    public function __construct(
        public readonly int $id,
        public readonly float $montant,
        public readonly string $datePaiement,
        public readonly int $commandeId,
        public readonly string $moyen,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            montant: (float) $row->montant,
            datePaiement: $row->date_paiement,
            commandeId: (int) $row->commande_id,
            moyen: $row->moyen,
        );
    }
}