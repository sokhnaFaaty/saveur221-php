<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Avis;

interface AvisRepositoryInterface
{
    /** @return Avis[] */
    public function findAll(): array;

    public function findByCommande(int $commandeId): ?Avis;

    public function create(int $clientId, int $commandeId, int $note, ?string $commentaire): Avis;

    public function delete(int $id): void;
}