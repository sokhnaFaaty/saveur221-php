<?php

declare(strict_types=1);

namespace App\Models;

class Commande
{
    public const EN_ATTENTE = 'EN_ATTENTE';
    public const EN_PREPARATION = 'EN_PREPARATION';
    public const PRETE = 'PRETE';
    public const RETIREE = 'RETIREE';
    public const ANNULEE = 'ANNULEE';

    /** @param LigneCommande[] $lignes */
    public function __construct(
        public readonly int $id,
        public readonly string $numCommande,
        public readonly string $dateCommande,
        public readonly float $total,
        public readonly string $statut,
        public readonly int $clientId,
        public readonly array $lignes = [],
    ) {}

    public static function fromRow(object $row, array $lignes = []): self
    {
        return new self(
            id: (int) $row->id,
            numCommande: $row->num_commande,
            dateCommande: $row->date_commande,
            total: (float) $row->total,
            statut: $row->statut,
            clientId: (int) $row->client_id,
            lignes: $lignes,
        );
    }

    // Traduction directe de Statut.peutTransitionnerVers() en Java.
    public function peutTransitionnerVers(string $cible): bool
    {
        if (in_array($this->statut, [self::RETIREE, self::ANNULEE], true)) {
            return false;
        }
        if ($cible === self::ANNULEE) {
            return true;
        }
        return match ($this->statut) {
            self::EN_ATTENTE => $cible === self::EN_PREPARATION,
            self::EN_PREPARATION => $cible === self::PRETE,
            self::PRETE => $cible === self::RETIREE,
            default => false,
        };
    }
}