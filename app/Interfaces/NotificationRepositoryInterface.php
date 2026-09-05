<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Notification;

interface NotificationRepositoryInterface
{
    public function create(string $type, string $message, ?string $lien, string $roleCible): void;

    /** @return Notification[] */
    public function findForRole(string $role): array;

    public function countUnread(string $role): int;

    public function markAsRead(int $id): void;
}