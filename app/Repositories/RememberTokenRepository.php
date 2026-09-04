<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RememberTokenRepositoryInterface;
use Core\Database;

class RememberTokenRepository implements RememberTokenRepositoryInterface
{
    public function findByHash(string $tokenHash): ?array
    {
        $sql = 'SELECT user_type, user_id FROM remember_tokens WHERE token_hash = ? AND expires_at > NOW()';
        $rows = Database::executeSelect($sql, [$tokenHash]);
        return $rows === [] ? null : [$rows[0]->user_type, (int) $rows[0]->user_id];
    }

    public function create(string $userType, int $userId, string $tokenHash, string $expiresAt): void
    {
        $sql = 'INSERT INTO remember_tokens (user_type, user_id, token_hash, expires_at) VALUES (?, ?, ?, ?)';
        Database::executeUpdate($sql, [$userType, $userId, $tokenHash, $expiresAt]);
    }

    public function deleteByHash(string $tokenHash): void
    {
        Database::executeUpdate('DELETE FROM remember_tokens WHERE token_hash = ?', [$tokenHash]);
    }
}