<?php

declare(strict_types=1);

namespace App\Interfaces;

interface RememberTokenRepositoryInterface
{
    /** @return array{0: string, 1: int}|null [user_type, user_id] */
    public function findByHash(string $tokenHash): ?array;

    public function create(string $userType, int $userId, string $tokenHash, string $expiresAt): void;

    public function deleteByHash(string $tokenHash): void;
}