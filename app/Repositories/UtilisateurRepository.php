<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\UtilisateurRepositoryInterface;
use App\Models\Utilisateur;
use Core\Database;

class UtilisateurRepository implements UtilisateurRepositoryInterface
{
    private const NON_SUPPRIME = ' AND deleted_at IS NULL';

    public function findById(int $id): ?Utilisateur
    {
        $sql = 'SELECT * FROM utilisateurs WHERE id = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$id]);
        return $rows === [] ? null : Utilisateur::fromRow($rows[0]);
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        $sql = 'SELECT * FROM utilisateurs WHERE email = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$email]);
        return $rows === [] ? null : Utilisateur::fromRow($rows[0]);
    }
}