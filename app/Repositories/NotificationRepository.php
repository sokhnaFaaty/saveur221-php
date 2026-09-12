<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use Core\Database;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(string $type, string $message, ?string $lien, string $roleCible, ?int $clientId = null): void
    {
        Database::executeUpdate(
            'INSERT INTO notifications (type, message, lien, role_cible, client_id) VALUES (?, ?, ?, ?, ?)',
            [$type, $message, $lien, $roleCible, $clientId]
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

    public function findForClient(int $clientId): array
    {
        $sql = 'SELECT * FROM notifications WHERE client_id = ? ORDER BY created_at DESC LIMIT 20';
        return array_map(Notification::fromRow(...), Database::executeSelect($sql, [$clientId]));
    }

    public function countUnreadForClient(int $clientId): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM notifications WHERE client_id = ? AND lue = false';
        $rows = Database::executeSelect($sql, [$clientId]);
        return (int) $rows[0]->total;
    }

    public function markAsReadForClient(int $id, int $clientId): void
    {
        Database::executeUpdate('UPDATE notifications SET lue = true WHERE id = ? AND client_id = ?', [$id, $clientId]);
    }
}