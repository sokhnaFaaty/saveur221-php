<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Utilisateur;

interface UtilisateurRepositoryInterface
{
    public function findById(int $id): ?Utilisateur;
    public function findByEmail(string $email): ?Utilisateur;
}