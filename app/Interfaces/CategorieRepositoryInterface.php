<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Categorie;

interface CategorieRepositoryInterface
{
    public function findById(int $id): ?Categorie;

    /** @return Categorie[] */
    public function findAll(): array;

    /** @return Categorie[] */
    public function search(string $motCle): array;

    public function contientDesProduits(int $categorieId): bool;

    public function create(array $data): Categorie;

    public function update(int $id, array $data): void;

    public function delete(int $id): void;
}