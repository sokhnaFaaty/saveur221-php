<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Commande;

interface CommandeRepositoryInterface
{
    public function findById(int $id): ?Commande;

    /** @return Commande[] */
    public function findByClient(int $clientId): array;

    /** @return Commande[] */
    public function findAll(): array;

    /** @return Commande[] */
    public function findByStatut(string $statut): array;

    /** @param array{produit_id:int, quantite:int, instructions:?string}[] $lignesPanier */
    public function create(int $clientId, array $lignesPanier): Commande;

    public function updateStatut(int $id, string $statut): void;
}