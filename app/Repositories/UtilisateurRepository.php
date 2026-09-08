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
    public function findAll(): array
{
    return array_map(Utilisateur::fromRow(...), Database::executeSelect('SELECT * FROM utilisateurs WHERE deleted_at IS NULL ORDER BY nom'));
}

public function create(array $data): Utilisateur
{
    $sql = 'INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, actif)
            VALUES (?, ?, ?, ?, ?, ?, true) RETURNING id';
    $rows = Database::executeSelect($sql, [$data['nom'], $data['prenom'], $data['email'], $data['mot_de_passe'], $data['telephone'], $data['role']]);
    return $this->findById((int) $rows[0]->id);
}

public function update(int $id, array $data): void
{
    Database::executeUpdate('UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ?, role = ? WHERE id = ?',
        [$data['nom'], $data['prenom'], $data['email'], $data['telephone'], $data['role'], $id]);
}

public function updateProfil(int $id, string $nom, string $prenom, string $email, ?string $telephone, ?string $image): void
{
    Database::executeUpdate('UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ?, image = ? WHERE id = ?',
        [$nom, $prenom, $email, $telephone, $image, $id]);
}

public function updateMotDePasse(int $id, string $motDePasse): void
{
    Database::executeUpdate('UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?', [$motDePasse, $id]);
}

    public function updateStatut(int $id, bool $actif): void
    {
        $actif = (int) (bool) $actif;
        Database::executeUpdate('UPDATE utilisateurs SET actif = ? WHERE id = ?', [$actif, $id]);
    }

public function delete(int $id): void
{
    Database::executeUpdate('UPDATE utilisateurs SET deleted_at = NOW() WHERE id = ?', [$id]);
}

public function findDeleted(): array
{
    return array_map(Utilisateur::fromRow(...), Database::executeSelect('SELECT * FROM utilisateurs WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC'));
}

public function restore(int $id): void
{
    Database::executeUpdate('UPDATE utilisateurs SET deleted_at = NULL WHERE id = ?', [$id]);
}

public function forceDelete(int $id): void
{
    Database::executeUpdate('DELETE FROM utilisateurs WHERE id = ?', [$id]);
}
}