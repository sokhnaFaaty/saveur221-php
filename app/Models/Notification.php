<?php

declare(strict_types=1);

namespace App\Models;

class Notification
{
    public function __construct(
        public readonly int $id,
        public readonly string $type,
        public readonly string $message,
        public readonly ?string $lien,
        public readonly string $roleCible,
        public readonly bool $lue,
        public readonly string $createdAt,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            type: $row->type,
            message: $row->message,
            lien: $row->lien,
            roleCible: $row->role_cible,
            lue: (bool) $row->lue,
            createdAt: $row->created_at,
        );
    }
}