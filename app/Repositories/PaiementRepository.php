<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Paiement;
use Core\Database;

class PaiementRepository implements PaiementRepositoryInterface
{
    public function findById(int $id): ?Paiement
    {
        $rows = Database::executeSelect('SELECT * FROM paiements WHERE id = ?', [$id]);
        return $rows === [] ? null : Paiement::fromRow($rows[0]);
    }

    public function findByCommande(int $commandeId): array
    {
        $sql = 'SELECT * FROM paiements WHERE commande_id = ? ORDER BY date_paiement';
        return array_map(Paiement::fromRow(...), Database::executeSelect($sql, [$commandeId]));
    }

    public function findAll(): array
    {
        return array_map(Paiement::fromRow(...), Database::executeSelect('SELECT * FROM paiements ORDER BY date_paiement'));
    }

    public function sommePaiements(int $commandeId): float
    {
        $sql = 'SELECT COALESCE(SUM(montant), 0) AS total FROM paiements WHERE commande_id = ?';
        $rows = Database::executeSelect($sql, [$commandeId]);
        return (float) $rows[0]->total;
    }

    public function create(int $commandeId, float $montant, string $moyen): Paiement
    {
        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            $rows = Database::executeSelect(
                'INSERT INTO paiements (montant, commande_id, moyen) VALUES (?, ?, ?) RETURNING id, date_paiement',
                [$montant, $commandeId, $moyen]
            );
            $paiementId = (int) $rows[0]->id;

            $numeroRecu = sprintf('REC-%s-%05d', date('Y'), $paiementId);
            Database::executeUpdate(
                'INSERT INTO recus (numero, montant, paiement_id) VALUES (?, ?, ?)',
                [$numeroRecu, $montant, $paiementId]
            );

            $pdo->commit();
            return new Paiement($paiementId, $montant, $rows[0]->date_paiement, $commandeId, $moyen);
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}