<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\AvisRepositoryInterface;
use App\Models\Avis;
use Core\Database;
use Exceptions\AvisDejaLaisseException;

class AvisRepository implements AvisRepositoryInterface
{
    private const NON_SUPPRIME = ' AND a.deleted_at IS NULL';
    private const SELECT_BASE =
        'SELECT a.*, c.nom AS client_nom, c.prenom AS client_prenom
         FROM avis a JOIN clients c ON c.id = a.client_id ';

    public function findAll(): array
    {
        $sql = self::SELECT_BASE . 'WHERE 1=1' . self::NON_SUPPRIME . ' ORDER BY a.date_avis DESC';
        return array_map(Avis::fromRow(...), Database::executeSelect($sql));
    }

    public function findByCommande(int $commandeId): ?Avis
    {
        $sql = self::SELECT_BASE . 'WHERE a.commande_id = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$commandeId]);
        return $rows === [] ? null : Avis::fromRow($rows[0]);
    }

    public function create(int $clientId, int $commandeId, int $note, ?string $commentaire): Avis
    {
        if ($this->findByCommande($commandeId) !== null) {
            throw new AvisDejaLaisseException('Un avis a deja ete laisse pour cette commande.');
        }

        $sql = 'INSERT INTO avis (note, commentaire, client_id, commande_id) VALUES (?, ?, ?, ?) RETURNING id';
        $rows = Database::executeSelect($sql, [$note, $commentaire, $clientId, $commandeId]);

        return $this->findByCommande($commandeId) ?? throw new \RuntimeException('Avis introuvable apres creation');
    }

    public function delete(int $id): void
    {
        Database::executeUpdate('UPDATE avis SET deleted_at = NOW() WHERE id = ?', [$id]);
    }
}