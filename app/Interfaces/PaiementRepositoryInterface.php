<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Paiement;

interface PaiementRepositoryInterface
{
    public function findById(int $id): ?Paiement;

    /** @return Paiement[] */
    public function findByCommande(int $commandeId): array;

    /** @return Paiement[] */
    public function findAll(): array;

    public function sommePaiements(int $commandeId): float;

    public function create(int $commandeId, float $montant, string $moyen): Paiement;
}