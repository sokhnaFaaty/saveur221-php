<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RecuRepositoryInterface;
use App\Models\Recu;
use Core\Database;

class RecuRepository implements RecuRepositoryInterface
{
    public function findByPaiement(int $paiementId): ?Recu
    {
        $rows = Database::executeSelect('SELECT * FROM recus WHERE paiement_id = ?', [$paiementId]);
        return $rows === [] ? null : Recu::fromRow($rows[0]);
    }
}