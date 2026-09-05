<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use Core\Database;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(string $type, string $message, ?string $lien, string $roleCible): void
    {
        Database::executeUpdate(
            'INSERT INTO notifications (type, message, lien, role_cible) VALUES (?, ?, ?, ?)',
            [$type, $message, $lien, $roleCible]
        );
    }

    public function findForRole(string $role): array
    {
        $sql = 'SELECT * FROM notifications WHERE role_cible = ? ORDER BY created_at DESC LIMIT 20';
        return array_map(Notification::fromRow(...), Database::executeSelect($sql, [$role]));
    }

    public function countUnread(string $role): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM notifications WHERE role_cible = ? AND lue = false';
        $rows = Database::executeSelect($sql, [$role]);
        return (int) $rows[0]->total;
    }

    public function markAsRead(int $id): void
    {
        Database::executeUpdate('UPDATE notifications SET lue = true WHERE id = ?', [$id]);
    }
}