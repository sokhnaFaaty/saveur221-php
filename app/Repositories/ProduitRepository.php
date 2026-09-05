<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Produit;
use Core\Database;
use Exceptions\StockInsuffisantException;

class ProduitRepository implements ProduitRepositoryInterface
{
    private const NON_SUPPRIME = ' AND p.deleted_at IS NULL';

    // Jointure avec categories, comme dans ton ProduitRepositoryImpl.java (cat_libelle)
    private const SELECT_BASE =
        'SELECT p.*, c.libelle AS categorie_libelle FROM produits p JOIN categories c ON p.categorie_id = c.id ';

    public function findById(int $id): ?Produit
    {
        $sql = self::SELECT_BASE . 'WHERE p.id = ?' . self::NON_SUPPRIME;
        $rows = Database::executeSelect($sql, [$id]);
        return $rows === [] ? null : Produit::fromRow($rows[0]);
    }

    public function findAll(): array
    {
        $sql = self::SELECT_BASE . 'WHERE p.deleted_at IS NULL ORDER BY p.libelle';
        return array_map(Produit::fromRow(...), Database::executeSelect($sql));
    }

    public function findByCategorie(int $categorieId): array
    {
        $sql = self::SELECT_BASE . 'WHERE p.categorie_id = ?' . self::NON_SUPPRIME . ' ORDER BY p.libelle';
        return array_map(Produit::fromRow(...), Database::executeSelect($sql, [$categorieId]));
    }

    public function search(string $motCle): array
    {
        $sql = self::SELECT_BASE . 'WHERE p.libelle ILIKE ?' . self::NON_SUPPRIME . ' ORDER BY p.libelle';
        return array_map(Produit::fromRow(...), Database::executeSelect($sql, ['%' . $motCle . '%']));
    }

    public function findEnRupture(): array
    {
        $sql = self::SELECT_BASE . 'WHERE p.quantite_stock <= 0' . self::NON_SUPPRIME . ' ORDER BY p.libelle';
        return array_map(Produit::fromRow(...), Database::executeSelect($sql));
    }

    public function findStockFaible(): array
    {
        $sql = self::SELECT_BASE
            . 'WHERE p.quantite_stock > 0 AND p.quantite_stock <= p.seuil_alerte'
            . self::NON_SUPPRIME . ' ORDER BY p.libelle';
        return array_map(Produit::fromRow(...), Database::executeSelect($sql));
    }

    public function create(array $data): Produit
    {
        $sql = 'INSERT INTO produits
                (libelle, description, prix, quantite_stock, categorie_id, image, seuil_alerte, temps_preparation, calories)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING id';

        $rows = Database::executeSelect($sql, [
            $data['libelle'], $data['description'] ?? null, $data['prix'], $data['quantite_stock'],
            $data['categorie_id'], $data['image'] ?? null, $data['seuil_alerte'] ?? 5,
            $data['temps_preparation'] ?? null, $data['calories'] ?? null,
        ]);

        return $this->findById((int) $rows[0]->id);
    }

    public function update(int $id, array $data): void
    {
        $sql = 'UPDATE produits SET libelle = ?, description = ?, prix = ?, categorie_id = ?, image = ?,
                seuil_alerte = ?, temps_preparation = ?, calories = ? WHERE id = ?';

        Database::executeUpdate($sql, [
            $data['libelle'], $data['description'] ?? null, $data['prix'], $data['categorie_id'],
            $data['image'] ?? null, $data['seuil_alerte'] ?? 5,
            $data['temps_preparation'] ?? null, $data['calories'] ?? null, $id,
        ]);
    }

    public function delete(int $id): void
    {
        Database::executeUpdate('UPDATE produits SET deleted_at = NOW() WHERE id = ?', [$id]);
    }

    public function diminuerStock(int $id, int $quantite): void
    {
        $produit = $this->findById($id);
        if ($produit === null) {
            throw new \Exceptions\ProduitInexistantException("Aucun produit trouve avec l'id $id");
        }
        if ($quantite > $produit->quantiteStock) {
            throw new StockInsuffisantException(
                "Stock insuffisant pour \"{$produit->libelle}\" : demande=$quantite, disponible={$produit->quantiteStock}"
            );
        }
        Database::executeUpdate('UPDATE produits SET quantite_stock = quantite_stock - ? WHERE id = ?', [$quantite, $id]);
    }

    public function restaurerStock(int $id, int $quantite): void
    {
        Database::executeUpdate('UPDATE produits SET quantite_stock = quantite_stock + ? WHERE id = ?', [$quantite, $id]);
    }
}
