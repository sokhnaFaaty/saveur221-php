<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\FactureRepositoryInterface;
use App\Models\Facture;
use Core\Database;

class FactureRepository implements FactureRepositoryInterface
{
    public function findByCommande(int $commandeId): ?Facture
    {
        $rows = Database::executeSelect('SELECT * FROM factures WHERE commande_id = ?', [$commandeId]);
        return $rows === [] ? null : Facture::fromRow($rows[0]);
    }
}