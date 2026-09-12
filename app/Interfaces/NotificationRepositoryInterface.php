<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Notification;

interface NotificationRepositoryInterface
{
    public function create(string $type, string $message, ?string $lien, string $roleCible, ?int $clientId = null): void;

    /** @return Notification[] */
    public function findForRole(string $role): array;

    public function countUnread(string $role): int;

    public function markAsRead(int $id): void;

    /** @return Notification[] */
    public function findForClient(int $clientId): array;

    public function countUnreadForClient(int $clientId): int;

    public function markAsReadForClient(int $id, int $clientId): void;
}