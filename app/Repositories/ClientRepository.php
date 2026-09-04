<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use Core\Database;

class ClientRepository implements ClientRepositoryInterface
{
    private const NON_SUPPRIME = ' AND deleted_at IS NULL';

    public function findById(int $id): ?Client
    {
        $sql = 'SELECT * FROM clients WHERE id = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$id]);
        return $rows === [] ? null : Client::fromRow($rows[0]);
    }

    public function findByEmail(string $email): ?Client
    {
        $sql = 'SELECT * FROM clients WHERE email = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$email]);
        return $rows === [] ? null : Client::fromRow($rows[0]);
    }

    public function findByTelephone(string $telephone): ?Client
    {
        $sql = 'SELECT * FROM clients WHERE telephone = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$telephone]);
        return $rows === [] ? null : Client::fromRow($rows[0]);
    }

    public function create(array $data): Client
    {
        $sql = 'INSERT INTO clients (nom, prenom, telephone, adresse, email, mot_de_passe, image)
                VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING *';

        $rows = Database::executeSelect($sql, [
            $data['nom'],
            $data['prenom'],
            $data['telephone'],
            $data['adresse'] ?? null,
            $data['email'],
            $data['mot_de_passe'],
            $data['image'] ?? null,
        ]);

        return Client::fromRow($rows[0]);
    }

    public function update(int $id, array $data): void
    {
        $sql = 'UPDATE clients SET nom = ?, prenom = ?, telephone = ?, adresse = ?, email = ?, image = ? WHERE id = ?';
        Database::executeUpdate($sql, [
            $data['nom'],
            $data['prenom'],
            $data['telephone'],
            $data['adresse'] ?? null,
            $data['email'],
            $data['image'] ?? null,
            $id,
        ]);
    }
}