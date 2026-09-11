<?php

declare(strict_types=1);

namespace App\Models;

class Categorie
{
    public function __construct(
        public readonly int $id,
        public readonly string $libelle,
        public readonly ?string $description,
        public readonly ?string $image,
        public readonly ?string $deletedAt = null,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            libelle: $row->libelle,
            description: $row->description,
            image: $row->image ?? null,
            deletedAt: $row->deleted_at ?? null,
        );
    }

    public function supprimeLe(): string
    {
        return $this->deletedAt ? date('d/m/Y H:i', strtotime((string) $this->deletedAt)) : '';
    }
}