<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Utilisateur;

interface UtilisateurRepositoryInterface
{
    public function findById(int $id): ?Utilisateur;
    public function findByEmail(string $email): ?Utilisateur;
    public function findAll(): array;
    public function create(array $data): \App\Models\Utilisateur;
    public function update(int $id, array $data): void;
    public function updateStatut(int $id, bool $actif): void;
    public function delete(int $id): void;
}
