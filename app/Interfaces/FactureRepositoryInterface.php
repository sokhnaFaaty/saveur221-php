<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Facture;

interface FactureRepositoryInterface
{
    public function findByCommande(int $commandeId): ?Facture;
}