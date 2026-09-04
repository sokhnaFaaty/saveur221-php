<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Client;

interface ClientRepositoryInterface
{
    public function findById(int $id): ?Client;
    public function findByEmail(string $email): ?Client;
    public function findByTelephone(string $telephone): ?Client;
    public function create(array $data): Client;
    public function update(int $id, array $data): void;
}