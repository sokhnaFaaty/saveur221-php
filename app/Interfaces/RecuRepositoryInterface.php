<?php
declare(strict_types=1);
namespace App\Interfaces;
use App\Models\Recu;

interface RecuRepositoryInterface
{
    public function findByPaiement(int $paiementId): ?Recu;
}