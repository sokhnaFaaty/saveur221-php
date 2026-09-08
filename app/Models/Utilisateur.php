<?php

declare(strict_types=1);

namespace App\Models;

class Utilisateur
{
    public function __construct(
        public readonly int $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $email,
        public readonly string $motDePasse,
        public readonly ?string $telephone,
        public readonly string $role,
        public readonly bool $actif,
        public readonly ?string $image,
        public readonly ?string $deletedAt = null,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            nom: $row->nom,
            prenom: $row->prenom,
            email: $row->email,
            motDePasse: $row->mot_de_passe,
            telephone: $row->telephone,
            role: $row->role,
            actif: (bool) $row->actif,
            image: $row->image,
            deletedAt: $row->deleted_at ?? null,
        );
    }

    public function supprimeLe(): string
    {
        return $this->deletedAt ? date('d/m/Y H:i', strtotime((string) $this->deletedAt)) : '';
    }

    public function nomComplet(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }
}