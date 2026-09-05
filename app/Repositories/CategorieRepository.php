<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\CategorieRepositoryInterface;
use App\Models\Categorie;
use Core\Database;

class CategorieRepository implements CategorieRepositoryInterface
{
    private const NON_SUPPRIME = ' AND deleted_at IS NULL';

    public function findById(int $id): ?Categorie
    {
        $sql = 'SELECT * FROM categories WHERE id = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$id]);
        return $rows === [] ? null : Categorie::fromRow($rows[0]);
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM categories WHERE deleted_at IS NULL ORDER BY libelle';
        return array_map(Categorie::fromRow(...), Database::executeSelect($sql));
    }

    public function search(string $motCle): array
    {
        $sql = 'SELECT * FROM categories WHERE libelle ILIKE ?' . self::NON_SUPPRIME . ' ORDER BY libelle';
        $rows = Database::executeSelect($sql, ['%' . $motCle . '%']);
        return array_map(Categorie::fromRow(...), $rows);
    }

    public function contientDesProduits(int $categorieId): bool
    {
        $sql = 'SELECT COUNT(*) AS total FROM produits WHERE categorie_id = ? AND deleted_at IS NULL';
        $rows = Database::executeSelect($sql, [$categorieId]);
        return (int) $rows[0]->total > 0;
    }

    public function create(array $data): Categorie
    {
        $sql = 'INSERT INTO categories (libelle, description) VALUES (?, ?) RETURNING *';
        $rows = Database::executeSelect($sql, [$data['libelle'], $data['description'] ?? null]);
        return Categorie::fromRow($rows[0]);
    }

    public function update(int $id, array $data): void
    {
        $sql = 'UPDATE categories SET libelle = ?, description = ? WHERE id = ?';
        Database::executeUpdate($sql, [$data['libelle'], $data['description'] ?? null, $id]);
    }

    public function delete(int $id): void
    {
        if ($this->contientDesProduits($id)) {
            throw new \Exceptions\ValidationException(
                'Impossible de supprimer : cette categorie contient des produits.'
            );
        }
        Database::executeUpdate('UPDATE categories SET deleted_at = NOW() WHERE id = ?', [$id]);
    }
}