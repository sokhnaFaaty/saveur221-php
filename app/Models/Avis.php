<?php

declare(strict_types=1);

namespace App\Models;

class Avis
{
    public function __construct(
        public readonly int $id,
        public readonly int $note,
        public readonly ?string $commentaire,
        public readonly string $dateAvis,
        public readonly int $clientId,
        public readonly ?string $clientNom,
        public readonly ?string $clientPrenom,
        public readonly int $commandeId,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            note: (int) $row->note,
            commentaire: $row->commentaire,
            dateAvis: $row->date_avis,
            clientId: (int) $row->client_id,
            clientNom: $row->client_nom ?? null,
            clientPrenom: $row->client_prenom ?? null,
            commandeId: (int) $row->commande_id,
        );
    }
}