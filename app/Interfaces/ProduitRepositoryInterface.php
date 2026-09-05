<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Produit;

interface ProduitRepositoryInterface
{
    public function findById(int $id): ?Produit;

    /** @return Produit[] */
    public function findAll(): array;

    /** @return Produit[] */
    public function findByCategorie(int $categorieId): array;

    /** @return Produit[] */
    public function search(string $motCle): array;

    /** @return Produit[] */
    public function findEnRupture(): array;

    /** @return Produit[] */
    public function findStockFaible(): array;

    public function create(array $data): Produit;

    public function update(int $id, array $data): void;

    public function delete(int $id): void;

    public function diminuerStock(int $id, int $quantite): void;

    public function restaurerStock(int $id, int $quantite): void;
}